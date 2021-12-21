<div class="gallery-selector-frame">
    <button id="public-gallery-selector" class="gallery-selector" type="button">
        <i class='bx bx-world gallery-selector-icon'></i>
        Public
    </button>
    <?php if($isOwnProfile === true) echo "
    <button id='private-gallery-selector' class='gallery-selector' type='button'>
        <i class='bx bxs-hide gallery-selector-icon'></i>
        Hidden
    </button>"; ?>
    <?php if($isOwnProfile === true) echo "
    <button id='saved-gallery-selector' class='gallery-selector' type='button'>
        <i class='bx bxs-bookmark-alt gallery-selector-icon'></i>
        Saved
    </button>"; ?>
</div>
<div class="gallery-container">
    <div class="gallery-delimiter-upper"></div>
    <div id="public-gallery" class="gallery"></div>
    <?php if($isOwnProfile === true) echo "<div id='private-gallery' class='gallery'></div>"; ?>
    <?php if($isOwnProfile === true) echo "<div id='saved-gallery' class='gallery'></div>"; ?>
    <div class="gallery-delimiter-lower"></div>
</div>