# Category List
A quick plugin for simple category ordering in EE5

## Usage

Variables are namespaced, so it doesn't mess up EE's native category stuff. It also catches custom category fields (icon, in this case)

```
{exp:category_list channel="resource" order_by="category_name" sort="asc"}
	<div class="card box" data-cat="{cl:category_url_title}">
		<a href="{site_url}members?cat={cl:category_id}" class="content-wrapper-link">
			<div class="service-element">
				<span class="fa fa-{cl:icon}"></span>
			</div>
			<div class="service-info">
				<h3>{cl:category_name}</h3>
			</div>
		</a>
	</div>
{/exp:category_list}
```

You can also grab a category ID by the category URL title!

```
{preload_replace:category_i_want_to_use='{exp:category_list channel="blog" order_by="category_name" sort="asc" cat_url_title="awesomeness"}{cl:category_id}{/exp:category_list}'}

{exp:channel:entries category="{category_i_want_to_use}"}
	{title}
{/exp:channel:entries}
```