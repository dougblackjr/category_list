<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use EllisLab\ExpressionEngine\Library\Data\Collection;

class Category_list {

	public $return_data = "";

	public function __construct()
	{

        // Get params
        $tagdata = ee()->TMPL->tagdata;

        $channelName = ee()->TMPL->fetch_param('channel', '');
        $orderBy = ee()->TMPL->fetch_param('order_by','');
        $catUrlTitle = ee()->TMPL->fetch_param('cat_url_title');
        $sort = ee()->TMPL->fetch_param('sort', 'ASC');

        $categories = $this->getCategories($channelName, $orderBy, $sort, $catUrlTitle);

        // Ship them to the template
		$output = ee()->TMPL->parse_variables($tagdata, $categories);

		$this->return_data = $output;

	}

	public function getCategories($channelName, $order_by, $sort, $catUrlTitle = null)
	{

		$site = ee('Model')->get('Site')
					->filter('site_id', ee()->config->item('site_id'))
					->first();

		$channel =  ee('Model')
						->get('Channel')
						->filter('site_id', $site->site_id)
						->filter('channel_name', $channelName)
						->first();

		$categories = [];

		foreach ($channel->getCategoryGroups() as $cat_group)
		{

			foreach ($cat_group->Categories as $category) {

				if($catUrlTitle && $catUrlTitle !== $category->cat_url_title) continue;

				$catData = [
					'category_id'			=> $category->cat_id,
					'category_name'			=> $category->cat_name,
					'category_url_title'	=> $category->cat_url_title,
				];

				$customFields = $category->getCustomFields();

				foreach ($customFields as $value) {

					$catData[$value->getShortName()] = $value->getData();

				}

				$categories[] = $catData;

			}

		}

		$output = new Collection($categories);

		// Sort and ship
		$output = $output->sortBy($order_by);

		$finalOutput = [];

		foreach ($output as $key => $value) {
			$namespaced = [];
			foreach ($value as $valueKey => $valueValue) {
				$namespaced["cl:{$valueKey}"] = $valueValue;
			}
			$finalOutput[] = $namespaced;
		}

		return $finalOutput;

	}

	private function dd() {

		foreach (func_get_args() as $key => $value) {
			highlight_string("<?php\n " . var_export($value, true) . "?>");
		}

		echo '<script>document.getElementsByTagName("code")[0].getElementsByTagName("span")[1].remove() ;document.getElementsByTagName("code")[0].getElementsByTagName("span")[document.getElementsByTagName("code")[0].getElementsByTagName("span").length - 1].remove() ; </script>';
		die();

	}

}
