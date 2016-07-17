$(function () {
    $('[data-submenu]').submenupicker();
    $('.dropdown').find('a').unbind("click").click(function () {
        window.location.href = $(this).attr('href');
    });
    $.ajax({
        url: Routing.generate('widget.app.blog_category', {'id': $('#category-tree').data('actived')}),
        async: true,
        dataType: 'json',
        success: function(data){
            $tree = $('#category-tree').treeview({data: data,enableLinks: true,highlightSelected: false});
            $select = $tree.treeview('getSelected');
            if($select.length > 0){
                $tree.treeview('search', [ $select[0]['text'], {ignoreCase: true, exactMatch: false,  revealResults: true}]);
                $tree.treeview('revealNode',[$select[0]['nodeid'], { silent: true }]);
            }
        }
    });
});