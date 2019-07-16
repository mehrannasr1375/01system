<?php
/* ***************************************************************************************
 *       ACTION = CATEGORIES -> ALWAYS EXISTS ON THIS SCRIPT
 ***************************************************************************************/
if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2)) {
    return;
}

// insert cat
elseif (isset($_POST['send_cat'])){
    $cat_name=$_POST['title'];
    $cat_parent=$_POST['parentid'];
    Cat::insertCat($cat_name,$cat_parent);
    showCats();
}

// delete cat
elseif (isset($_POST['delete_cat'])){
    Cat::deleteCatById($_POST['delete_cat']);
    showCats();
}

// show all cats (default action on this script)
else {
    showCats();
}


function showCats()
{
?>
    <div class="statusbar"><i class="fa fa-object-group fa-2x d-inline-block "></i><span class="statusbar-p">گروه ها</span></div>

    <div class="row" id="categories">

        <div class="col-12 col-md card-3" id="show-cats" >
            <div class="card-3-header">
                <div class="card-3-baner header-purple"><i class="fa fa-object-group fa-3x text-light"></i></div>
                <p class="card-3-description">گروه ها</p>
                <h3 class="card-3-title title-purple"><?=Cat::getCatsCount();?>
                    <small>گروه</small>
                </h3>
            </div>
            <hr class="devider"/>
            <div class="card-3-footer">
                <ul class='shifttoleft1'>
                    <?php
                    $output="";
                    if ($cats=Cat::getCatsByParentId(1)){
                        foreach($cats as $cat){
                            $output.="<li>$cat->cat_name</li>";
                            if ($childs1=Cat::getCatsByParentId($cat->id)){
                                $output.="<ul class='shifttoleft2'>";
                                foreach($childs1 as $cat){
                                    $output.="<li>$cat->cat_name</li>";
                                    if ($childs2=Cat::getCatsByParentId($cat->id)){
                                        $output.="<ul class='shifttoleft3'>";
                                        foreach($childs2 as $cat){
                                            $output.="<li>$cat->cat_name</li>";
                                        }
                                        $output.="</ul>";
                                    }}
                                $output.="</ul>";
                            }}}
                    echo $output;
                    ?>
                </ul>
            </div>
        </div>

        <div class="col-12 col-md d-flex row">
            <div class="card-3 col-12 col-md-11">
                <div class="card-3-header">
                    <div class="card-3-baner header-green">
                        <i class="fa fa-save fa-3x text-light"></i>
                    </div>
                    <p class="card-3-description">گروه جدید</p>
                    <h3 class="card-3-title title-green"></h3>
                </div>
                <hr class="devider"/>
                <div class="card-3-footer mt-3">
                    <div id="new-cat">
                        <form method="post">
                            <div class="form-group">
                                <input class="form-control" type="text" name="title" placeholder="عنوان گروه">
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="parentid">
                                    <option value="1">بدون والد</option>
                                    <?php
                                    $output="";
                                    if ($cats=Cat::getCatsByParentId(1)) {
                                        foreach ($cats as $cat) {
                                            $output.="<option value='$cat->id'>$cat->cat_name</option>";
                                            if ($cats2=Cat::getCatsByParentId($cat->id)) {
                                                foreach ($cats2 as $cat) {
                                                    $output.="<option value='$cat->id'> _____ $cat->cat_name</option>";
                                                    if ($cats3=Cat::getCatsByParentId($cat->id)) {
                                                        foreach ($cats3 as $cat) {
                                                            $output.="<option value='$cat->id'> ............. $cat->cat_name</option>";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    echo $output;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control btn btn-outline-success" type="submit" name="send_cat" value="ایجاد"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-3 col-12 col-md-11">
                <div class="card-3-header">
                    <div class="card-3-baner header-red">
                        <i class="fa fa-remove fa-3x text-light pr-1"></i>
                    </div>
                    <p class="card-3-description">حذف گروه</p>
                    <h3 class="card-3-title title-red"></h3>
                </div>
                <hr class="devider"/>
                <div class="card-3-footer mt-3">
                    <div id="delete-cat">

                        <form id="delete-gp" method="post">
                            <div class="form-group">
                                <select class="select-cat form-control" name="delete_cat">
                                    <?php
                                    $output="";
                                    if($cats=Cat::getCatsByParentId(1)){
                                        foreach ($cats as $cat) {
                                            $output.="<option value='$cat->id'>$cat->cat_name</option>";
                                            if($childs1=Cat::getCatsByParentId($cat->id)){
                                                foreach ($childs1 as $cat){
                                                    $output.="<option value='$cat->id'> _____ $cat->cat_name</option>";
                                                    if($childs2=Cat::getCatsByParentId($cat->id)){
                                                        foreach ($childs2 as $cat){
                                                            $output.="<option value='$cat->id'> ............. $cat->cat_name</option>";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    echo $output;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control btn btn-outline-danger" type="submit" name="dalete_cat" value="حذف"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script> document.addEventListener('DOMContentLoaded', function(event) { $("#l6").siblings().removeClass("active"); $("#l6").addClass("active"); }) </script>
<?php
}

