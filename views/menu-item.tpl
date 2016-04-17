<md-button 
	class="<?= implode(' ',$menu_item['classes']) ?>" 
	<? if(empty($menu_item['children'])) : ?>
		href="<?= $menu_item['url'] ?>"
	<? else : ?>
		ng-click="$mdOpenMenu($event);"
		md-menu-origin
	<? endif ?>>
	<?= $menu_item['title'] ?>
</md-button>