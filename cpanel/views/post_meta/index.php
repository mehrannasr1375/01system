<!------------------------------------------------- PAGE CONTAINER ----------------------------------------------------------------------------------------------->
<div id="categories-page" class="row">



    <!-- right =================================================================================================================================================-->
    <div class="new-post-right col-9">


        <!-- cats -->
        <div class="mb-5">
            <!-- title -->
            <div class="d-flex justify-content-start pt-2">
                <i class="fa fa-2x fa-sitemap text-secondary"></i>
                <span style="font-size:13px; margin-right:10px;">گروه ها</span>
                <span class="text-vvsm text-info pr-4"><?=PostMeta::getCategoriesCount()?></span>
            </div>
            <hr>
            <!-- show cats part -->
            <div id="show-cats">
                <ul class='shifttoleft1 p-2'>
                    <?php
                    $output = "";
                    if ($cats = PostMeta::allCategoriesByParent(0)){
                        foreach ($cats as $cat){
                            $output .= "<li>$cat->title</li>";
                            if ($childs1 = PostMeta::allCategoriesByParent($cat->id)){
                                $output .= "<ul class='shifttoleft2'>";
                                foreach ($childs1 as $cat){
                                    $output .= "<li>$cat->title</li>";
                                    if ($childs2 = PostMeta::allCategoriesByParent($cat->id)){
                                        $output .= "<ul class='shifttoleft3'>";
                                        foreach ($childs2 as $cat){
                                            $output .= "<li>$cat->title</li>";
                                        }
                                        $output .= "</ul>";
                                    }
                                }
                                $output .= "</ul>";
                            }
                        }
                    }
                    echo $output;
                    ?>
                </ul>
            </div>
        </div>


        <!-- tags -->
        <div>
            <!-- title -->
            <div class="d-flex justify-content-start pt-2">
                <i class="fa fa-2x fa-tags text-secondary"></i>
                <span style="font-size:13px; margin-right:10px;">برچسب ها</span>
                <span class="text-vvsm text-info pr-4"><?=count(PostMeta::allTags())?></span>
            </div>
            <hr>
            <!-- show tags part -->
            <div id="show-tags">
                <ul class='shifttoleft1 p-2'>
                    <?php
                    if ($tags = PostMeta::allTags())
                        foreach ($tags as $tag)
                            echo "<li> $tag->title </li>";
                    ?>
                </ul>
            </div>
        </div>


    </div>



    <!-- left ==================================================================================================================================================-->
    <div class="new-post-left col-3">


        <!-- cats -->
        <div>

            <!-- create cat part --------------------------------------------------------------------------------------------------------------------------------->
            <div class="new-post-left-part mb-0">
                <form action="./controllers/post_meta/store.php?action=create_cat" method="POST">
                    <!-- part title -->
                    <p class="n-p-l-title"><i class="fa fa-plus"></i>ایجاد گروه</p>
                    <hr class="mt-0">
                    <!-- part body -->
                    <div class="n-p-l-body">
                        <!-- group name text -->
                        <input class="form-control input-create-category mb-3" type="text" name="cat_title" placeholder="عنوان گروه" autocomplete="off">
                        <!-- parent select control -->
                        <select class="form-control input-create-category" name="cat_parent">
                            <option value="0">بدون والد</option>
                            <?php
                            $output = "";
                            if ($cats = PostMeta::allCategoriesByParent(0)) {
                                foreach ($cats as $cat) {
                                    $output .= "<option value='$cat->id'>$cat->title</option>";
                                    if ($cats2 = PostMeta::allCategoriesByParent($cat->id)) {
                                        foreach ($cats2 as $cat) {
                                            $output .= "<option value='$cat->id'> _____ $cat->title</option>";
                                            if ($cats3 = PostMeta::allCategoriesByParent($cat->id)) {
                                                foreach ($cats3 as $cat) {
                                                    $output .= "<option value='$cat->id'> ............. $cat->title</option>";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            echo $output;
                            ?>
                        </select>
                        <hr class="mb-1">
                        <!-- btn save -->
                        <div class="form-group d-flex justify-content-end mb-0">
                            <input type="submit" class="btn btn-sm btn-info text-vsm" value="ذخیره" />
                        </div>
                    </div>
                </form>
            </div>

            <!-- delete cat part --------------------------------------------------------------------------------------------------------------------------------->
            <div class="new-post-left-part mb-5">
                <form action="./controllers/post_meta/destroy.php" method="POST">
                    <!-- part title -->
                    <p class="n-p-l-title"><i class="fa fa-remove"></i>حذف گروه</p>
                    <hr class="mt-0">
                    <!-- part body -->
                    <div class="n-p-l-body">
                        <!-- group select control -->
                        <select class="select-cat form-control input-create-category" name="id">
                            <?php
                            $output = "";
                            if ($cats = PostMeta::allCategoriesByParent(0)){
                                foreach ($cats as $cat) {
                                    $output .= "<option value='$cat->id'>$cat->title</option>";
                                    if ($childs1 = PostMeta::allCategoriesByParent($cat->id)){
                                        foreach ($childs1 as $cat){
                                            $output .= "<option value='$cat->id'> _____ $cat->title</option>";
                                            if ($childs2 = PostMeta::allCategoriesByParent($cat->id)){
                                                foreach ($childs2 as $cat){
                                                    $output .= "<option value='$cat->id'> __________ $cat->title</option>";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            echo $output;
                            ?>
                        </select>
                        <hr class="mb-1">
                        <!-- btn delete -->
                        <div class="form-group d-flex justify-content-end mb-0">
                            <button type="submit" class="btn btn-sm btn-danger text-vsm"><i class="fa fa-remove"></i>حذف</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>


        <!-- tags -->
        <div>

            <!-- create tag part --------------------------------------------------------------------------------------------------------------------------------->
            <div class="new-post-left-part mb-0">
                <form action="./controllers/post_meta/store.php?action=create_tag" method="POST">
                    <!-- part title -->
                    <p class="n-p-l-title"><i class="fa fa-plus"></i>ایجاد برچسب</p>
                    <hr class="mt-0">
                    <!-- part body -->
                    <div class="n-p-l-body">
                        <!-- group name text -->
                        <input class="form-control input-create-category mb-3" type="text" name="tag_title" placeholder="عنوان برچسب" autocomplete="off" >
                        <!-- btn save -->
                        <div class="form-group d-flex justify-content-end mb-0">
                            <button type="submit" class="btn btn-sm btn-info text-vsm">
                                <i class="fa fa-save"></i>
                                ذخیره
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- delete tag part --------------------------------------------------------------------------------------------------------------------------------->
            <div class="new-post-left-part">
                <form action="./controllers/post_meta/destroy.php" method="POST">
                    <!-- part title -->
                    <p class="n-p-l-title"><i class="fa fa-remove"></i>حذف برچسب</p>
                    <hr class="mt-0">
                    <!-- part body -->
                    <div class="n-p-l-body">
                        <!-- group select control -->
                        <select class="select-cat form-control input-create-category" name="id">
                            <?php
                            if ($tags = PostMeta::allTags())
                                foreach ($tags as $tag)
                                    echo "<option value='$tag->id'>$tag->title</option>";
                            ?>
                        </select>
                        <hr class="mb-1">
                        <!-- btn delete -->
                        <div class="form-group d-flex justify-content-end mb-0">
                            <button type="submit" class="btn btn-sm btn-danger text-vsm">
                                <i class="fa fa-remove"></i>
                                حذف
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>


    </div>



</div>



<!---------------------------------------------------- SCRIPTS ---------------------------------------------------------------------------------------------------------------------------------------------------->
<script>
    $(document).ready(function(event){
        $("#l8").siblings().removeClass("active"); $("#l7").addClass("active");
    });
</script>

