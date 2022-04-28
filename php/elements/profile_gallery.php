<div class="gallery-selector-frame">
    <?php
    $publicSelectorAttribute=$privateSelectorAttribute=$savedSelectorAttribute="";
        if(isset($_GET["gallery"]) && $isOwnProfile) {
            if($_GET["gallery"]=="private") $privateSelectorAttribute=" gallery-selected";
            else if($_GET["gallery"]=="saved") $savedSelectorAttribute=" gallery-selected";
            else $publicSelectorAttribute=" gallery-selected";
        }
        else $publicSelectorAttribute=" gallery-selected";
    ?>
    <?php echo  "
    <button id='public-gallery-selector' class='gallery-selector site-font".$publicSelectorAttribute."' type='button'>
        <i class='bx bx-world gallery-selector-icon'></i>
        Public
    </button>"; ?>
    <?php if($isOwnProfile === true) echo "
    <button id='private-gallery-selector' class='gallery-selector site-font".$privateSelectorAttribute."' type='button'>
        <i class='bx bxs-hide gallery-selector-icon'></i>
        Hidden
    </button>"; ?>
    <?php if($isOwnProfile === true) echo "
    <button id='saved-gallery-selector' class='gallery-selector site-font".$savedSelectorAttribute."' type='button'>
        <i class='bx bxs-bookmark-alt gallery-selector-icon'></i>
        Saved
    </button>"; ?>
</div>
<div class="gallery-container">
    <?php
        $publicGalleryAttribute=$privateGalleryAttribute=$savedGalleryAttribute=" hidden";
        if(isset($_GET["gallery"]) && $isOwnProfile) {
            if($_GET["gallery"]=="private") $privateGalleryAttribute="";
            else if($_GET["gallery"]=="saved") $savedGalleryAttribute="";
            else $publicGalleryAttribute="";
        }
        else $publicGalleryAttribute="";
    ?>
    <div class="gallery-delimiter-upper"></div>
    <?php echo "<div id='public-gallery' class='gallery".$publicGalleryAttribute."'></div>"; ?>
    <?php if($isOwnProfile === true) echo "<div id='private-gallery' class='gallery".$privateGalleryAttribute."'></div>"; ?>
    <?php if($isOwnProfile === true) echo "<div id='saved-gallery' class='gallery".$savedGalleryAttribute."'></div>"; ?>
    <div class="gallery-delimiter-lower"></div>
</div>