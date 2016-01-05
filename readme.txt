<!-- single product page -->

<!-- info, video, gallery and show pop up light boxes -->
<!-- rotate and open door change the main product image 
        -rotate is just a simple slider
        -open door changes the picture to the product with its door open with hotspots that contain information. May need x and y coordinate input fields on the back end so they can add as many as they want and can position them how they want. -->

<!-- info and rotate have the slider functionality(bootstrap) already. We can move forward with that for the rest of them or use a custom solution. Im good with either. -->

<!-- info lightbox html -->
<div class="product-lightbox-container" style="top: 20%;">
    <div data-id="info" class="modalx-container"><img class="modalx" src="assets/img/modalx.png" alt="x">
    </div>
    <div class="top-section" style="border-bottom-style: none;">
        <div id="product-info-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active"><img src="assets/img/test-img-1.png" alt="Info Image">
                </div>
                <div class="item"><img src="assets/img/test-img-2.png" alt="Info Image">
                </div>
                <div class="item"><img src="assets/img/test-img-3.png" alt="Info Image">
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-section" style="display: block;">
        <ol class="info-buttons">
            <li class="active" data-id="1">About</li>
            <li data-id="2">Specs</li>
            <li data-id="3">Technology</li>
            <li data-id="4">Design</li>
        </ol>
        <div class="content">
            <div class="active info-content-section info-content-section-1">Section 1</div>
            <div class="info-content-section info-content-section-2">Section 2</div>
            <div class="info-content-section info-content-section-3">Section 3</div>
            <div class="info-content-section info-content-section-4">Section 4</div>
        </div>
        <ol class="carousel-indicators info-carousel-indicators">
            <li data-target="#product-info-carousel" data-slide-to="0" class="active"></li>
            <li data-target="#product-info-carousel" data-slide-to="1"></li>
            <li data-target="#product-info-carousel" data-slide-to="2"></li>
        </ol>
    </div>
</div>

<!-- video/gallery lightbox html -->
<div class="product-lightbox-container" style="top: 20%;">
    <div data-id="video" class="modalx-container"><img class="modalx" src="assets/img/modalx.png" alt="x">
    </div>
    <div class="top-section" style="border-bottom-style: solid; border-bottom-width: 55px; border-bottom-color: rgb(255, 29, 37);">
        <div id="product-video-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/lqT_dPApj9U" frameborder="0"></iframe>
                    <div class="video-info-container">
                        <div class="carousel-caption">Title 1</div>
                        <div class="fullscreen"></div>
                    </div>
                </div>
                <div class="item">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/A45sjUX7mp0" frameborder="0"></iframe>
                    <div class="video-info-container">
                        <div class="carousel-caption">Title 2</div>
                        <div class="fullscreen"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="video-info-container">
            <div class="video-slider-controls">
                <div class="prev">
                    <a href="product-video-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow">
                    </a>
                </div>
                <div class="next">
                    <a href="product-video-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-section" style="display: none;"></div>
</div>

<!-- show lightbox html -->
<div class="product-lightbox-container" style="top: 20%;">
    <div data-id="show" class="modalx-container"><img class="modalx" src="assets/img/modalx.png" alt="x">
    </div>
    <div class="top-section" style="border-bottom-style: none;">
        <div id="product-video-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox"></div>
        </div>
    </div>
    <div class="bottom-section" style="display: block;">
        <div class="bottom-top-container">
            <div class="col-sm-2">
                <div class="video-slider-controls">
                    <div class="prev">
                        <a href="product-video-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow">
                        </a>
                    </div>
                    <div class="next">
                        <a href="product-video-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <h2>Title</h2>
            </div>
            <div class="col-sm-2">
                <div class="fullscreen"></div>
            </div>
        </div>
        <div class="bottom-bottom-container">
            <div class="prev">
                <a href="product-video-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow">
                </a>
            </div>
            <div class="bottom-slides">
                <div><img src="assets/img/show-thumb.png">
                </div>
                <div><img src="assets/img/show-thumb.png">
                </div>
                <div><img src="assets/img/show-thumb.png">
                </div>
            </div>
            <div class="next">
                <a href="product-video-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow">
                </a>
            </div>
        </div>
    </div>
</div>