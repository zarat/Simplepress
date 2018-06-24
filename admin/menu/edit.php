<?php 
$system = new system();
if( !$system->auth() ) header("Location: ../login.php");
?>
<link rel="stylesheet" type="text/css" href="../admin/menu/style.css">

<div class ="sp-content">

    <div class="sp-content-item">
    
        <div class="sp-content-item-head"><?php echo $system->_t('welcome_to_menu_edit'); ?></div>

        <div class="sp-content-item-body">
        
            <div id="load"></div>
        
            <input type="text" id="label" placeholder="Label" required> 
            <input type="text" id="link" placeholder="Link" required>
            <button id="submit">add</button>
            <input type="hidden" id="id">
            <br /><br />
        
            <div class="cf nestable-lists">
            
                <div class="dd" id="nestable">
        
                    <?php

                    $menu = $system->archive( array( "select" => "*", "from" => 'menu', "where" => "menu_id=$_GET[menu_id] order by sort") );
                     
                    $ref = [];
                    $items = [];
                    
                    foreach($menu as $data) {
                    
                        $thisRef = &$ref[$data['id']];
                        $thisRef['parent'] = $data['parent'];
                        $thisRef['label'] = $data['label'];
                        $thisRef['link'] = $data['link'];
                        $thisRef['id'] = $data['id'];
                    
                       if($data['parent'] == 0) {
                            $items[$data['id']] = &$thisRef;
                       } else {
                            $ref[$data['parent']]['child'][$data['id']] = &$thisRef;
                       }
                    
                    }
                     
                     
                    function get_menu($items,$class = 'dd-list') {
                        $html = "<ol class=\"".$class."\" id=\"menu-id\">";
                        foreach($items as $key=>$value) {
                            $html.= '<li class="dd-item dd3-item" data-id="'.$value['id'].'" >
                                        <div class="dd-handle dd3-handle"></div>
                                        <div class="dd3-content"><span id="label_show'.$value['id'].'">'.$value['label'].'</span> 
                                            <span class="span-right"> 
                                                <a class="edit-button" id="'.$value['id'].'" label="'.$value['label'].'" link="'.$value['link'].'" >edit</a>
                                                <a class="del-button" id="'.$value['id'].'">delete</a>
                                            </span> 
                                        </div>';
                            if(array_key_exists('child',$value)) {
                                $html .= get_menu($value['child'],'child');
                            }
                                $html .= "</li>";
                        }
                        $html .= "</ol>";
                        return $html;
                    }
                     
                    print get_menu($items);
                    
                    ?>
        
                </div>
                
            </div>
            
        </div>

    <input type="hidden" id="nestable-output">
    
    </div>
    
</div>
    
<div class="sp-sidebar">
</div>
    
<div style="clear:both;"></div>

<script src="./menu/jquery.nestable.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    var updateOutput = function(e) {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };
    $('#nestable').nestable({
        group: 1
    })
    .on('change', updateOutput);
    updateOutput($('#nestable').data('output', $('#nestable-output')));
    $('#nestable-menu').on('click', function(e) {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $("#load").hide();
    $("#submit").click(function(){
        $("#load").show();  
        var dataString = { 
            label : $("#label").val(),
            link : $("#link").val(),
            id : $("#id").val(),
            menu_id : <?php echo $_GET["menu_id"]; ?>
        };  
        $.ajax({
            type: "POST",
            url: "./menu/save_menu.php",
            data: dataString,
            dataType: "json",
            cache : false,
            success: function(data){
                if(data.type == "add"){
                   $("#menu-id").append(data.menu);
                } else if(data.type == 'edit'){
                   $("#label_show"+data.id).html(data.label);
                }
                $("#label").val("");
                $("#link").val("");
                $("#id").val("");
                $("#load").hide();
            } ,error: function(xhr, status, error) {
                alert(error);
            },
        });
    }); 
    $(".dd").on("change", function() {
        $("#load").show();     
        var dataString = { 
            data : $("#nestable-output").val(),
        };  
        $.ajax({
            type: "POST",
            url: "./menu/save.php",
            data: dataString,
            cache : false,
            success: function(data){
                $("#load").hide();
            } ,error: function(xhr, status, error) {
                alert(error);
            },
        });
    });  
    $("#save").click(function(){
        $("#load").show();    
        var dataString = { 
            data : $("#nestable-output").val(),
        };  
        $.ajax({
            type: "POST",
            url: "./menu/save.php",
            data: dataString,
            cache : false,
            success: function(data){
                $("#load").hide();  
            } ,error: function(xhr, status, error) {
                alert(error);
            },
        });
    });
    $(document).on("click",".del-button",function() {
        var x = confirm("Delete this menu?");
        var id = $(this).attr("id");
        if(x) {
            $("#load").show();
            $.ajax({
                type: "POST",
                url: "./menu/delete.php",
                data: { id : id },
                cache : false,
                success: function(data){
                    $("#load").hide();
                    $("li[data-id='" + id +"']").remove();
                } ,error: function(xhr, status, error) {
                    alert(error);
                },
            });
        }
    });  
    $(document).on("click",".edit-button",function() {
        var id = $(this).attr("id");
        var label = $(this).attr("label");
        var link = $(this).attr("link");
        $("#id").val(id);
        $("#label").val(label);
        $("#link").val(link);
    });
});
</script>
