<?php
require_once('BaseController.php');
class HomeController extends BaseController
{
    public function Index()
    {
        $this->Title = "Index";
        $this->View();
    }

    public function NotFound()
    {
        return $this->View();
    }

    public function Search()
    {
        $searchQuery = $this->Get['keywords'];

        $this->Title = 'Search: ' . $searchQuery;
        $this->Set('SearchQuery', $searchQuery);

        $this->Logging->Write($searchQuery);
        $results = array();
        if(!empty($searchQuery)){
            $resultItems =  $this->SearchItems($searchQuery);

            foreach($resultItems as $key => $value){
                $results[$key] = $value;
            }
        }

        $this->Set('Results', $results);

        return $this->View();
    }

    private function SearchItems($searchQuery)
    {
        $result = array();

        $exactDocumentName = $this->Models->Document->Where(array('Name' => $searchQuery));
        $exactDirectory = $this->Models->VirtualDirectory->Where(array('Name' => $searchQuery));
        $exactDocumentDescription = $this->Models->Document->Where(array('ShortDescription' => $searchQuery));

        $likeDocumentName = $this->Models->Document->Where(LikeCondition('Name', $searchQuery));
        $likeDirectory = $this->Models->VirtualDirectory->Where(LikeCondition('Name', $searchQuery));
        $likeDocumentDescription = $this->Models->Document->Where(LikeCondition('ShortDescription', $searchQuery));

        $itemsFound = new Collection();
        $itemsFound->AddRange($exactDocumentName);
        $itemsFound->AddRange($exactDocumentDescription);
        $itemsFound->AddRange($exactDirectory);
        $itemsFound->AddRange($likeDocumentName);
        $itemsFound->AddRange($likeDocumentDescription);
        $itemsFound->AddRange($likeDirectory);

        foreach($itemsFound as $item) {
            if(is_a($item, 'Document')) {
                $result[$item->GetHistoryPath()] = array(
                    'Header' => 'Document - ' . $item->Name,
                    'Link' => $item->GetHistoryPath(),
                    'Context' => $item->GetSearchResultContext(),
                );
            }else if(is_a($item, 'VirtualDirectory')){
                $result[$item->GetLinkPath()] = array(
                    'Header' => 'Directory - ' . $item->Name,
                    'Link' => $item->GetLinkPath(),
                    'Context' => $item->GetSearchResultContext(),
                );
            }
        }

        return $result;
    }
}