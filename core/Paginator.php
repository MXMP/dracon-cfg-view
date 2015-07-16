<?php
class Paginator {
    private $totalPages;
    private $currentPage;
    private $eventsCount;
    private $eventsPerPage;

    public function __construct($eventsCount, $perPage) {
        if ($eventsCount || $perPage) {
            $this->eventsCount = $eventsCount;
            $this->eventsPerPage = $perPage;
            $this->totalPages = ceil($eventsCount / $perPage);
        } else {
            throw new Exception("Couldn't create Paginator object, null parameters");
        }
    }
    
    public function getUI($currentPage, $linkPage, $count_show_pages = 16) {        
        if (!$currentPage) {
            throw new Exception("Couldn't render paginator, currentPage is null");
        } else {                                                
            $url_page = $linkPage . "?currentPage=";
            if ($this->totalPages > 1) {    
                $left = $currentPage - 1;
                $right = $this->totalPages - $currentPage;
                
                if ($left < floor($count_show_pages / 2)) {
                    $start = 1;
                } else {
                    $start = $currentPage - floor($count_show_pages / 2);
                }
                
                $end = $start + $count_show_pages - 1;
                
                if ($end > $this->totalPages) {
                    $start -= ($end - $this->totalPages);
                    $end = $this->totalPages;
                    
                    if ($start < 1) {
                        $start = 1;
                    }
                }
                //Дальше идёт вывод Pagination 
                echo '<ul class="pagination">';
                if ($currentPage != 1) {
                    echo "<li><a href=\"{$linkPage}\" title=\"Первая страница\"><span aria-hidden=\"true\">&laquo;</span><span aria-hidden=\"true\">&laquo;</span></a></li>";

                    if ($currentPage == 2) {
                        $test_str = $linkPage;          
                    } else {
                        $test_str = $url_page.($currentPage - 1);          
                    }

                    echo "<li><a href=\"$test_str\" title=\"Предыдущая страница\"><span aria-hidden=\"true\">&laquo;</span></a></li>";
                }

                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $currentPage) { 
                        echo "<li class=\"active\"><span>{$i}</span></li>";           
                    } else {
                        if ($i == 1) {
                            $test_str2 = $linkPage;            
                        } else {
                            $test_str2 = $url_page.$i;            
                        }
                        echo "<li><a href=\"{$test_str2}\">{$i}</a></li>";
                    }
                }

                if ($currentPage != $this->totalPages) {
                    echo "<li><a href=\"" . $url_page . ($currentPage + 1) . "\" title=\"Следующая страница\"><span aria-hidden=\"true\">&raquo;</span></a></li>";
                    echo "<li><a href=\"" . $url_page . $this->totalPages . "\" title=\"Последняя страница\"><span aria-hidden=\"true\">&raquo;</span><span aria-hidden=\"true\">&raquo;</span></a></li>";
                }
                echo '</ul>';
            }                                    
        }
    }
    
    public function getNumbers($currentPage) {
        if ($currentPage == 1) {
            $startNum = 0;
            $endNum = $this->eventsPerPage;
        } else {
            $startNum = (($currentPage - 1) * $this->eventsPerPage);
            $endNum = $currentPage * $this->eventsPerPage;
        }
        if ($endNum > $this->eventsCount) {
            $endNum = $this->eventsCount;
        }
        
        return array('startNum' => $startNum, 'endNum' => $endNum);
    }       
}