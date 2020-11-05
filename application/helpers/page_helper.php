<?php
/*
 * pagerのテンプレート作成
 */

if ( ! function_exists('pagitemp'))
{
    function pagitemp($t,$total)
    {

        $config['base_url'] = $t->basePath;
		$config['total_rows'] = count($total);
		$config['per_page'] = $t->limit;
		$config['full_tag_open'] = '<ul class="pagination pagination-sm m-0 float-right">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item"><a class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['prev_link'] = "前の".$t->limit."件";
        $config['next_link'] = "次の".$t->limit."件";
        return $config;
    }
}