<?php namespace Cms\Libs;

class Pagination {

    public $total = 0;
	public $page = 1;
	public $limit = 20;
	public $url = '';
	

    public function createLinks() {
        if ($this -> total == 0 || $this -> page == 0) {
            return '';
        }

        $num_pages = ceil($this -> total / $this -> limit);

        $output = '';

		$output .= '<ul class="pagination__wrap">';
		
		for($i=1;$i<=$num_pages;$i++) {
			if ($i == $this -> page) {
				$output .= '<li class="pagination__item pagination__item_active">';
			} else {
				$output .= '<li class="pagination__item">';
			}
			
			$output .= '<a class="pagination__link" href="'.str_replace('{page}', $i, $this -> url).'">'.$i."</a>" ;
			$output .= '</li>';
			
		}

		$output .= '</ul>';
		
        $output = preg_replace("#([^:])//+#", "\\1/", $output);


        if ($num_pages > 1) {
            return $output;
        }else{
			return;
		}

    }

}
