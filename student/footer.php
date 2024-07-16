<!-- Footer Section -->
<footer class="footer ttm-bgcolor-darkgrey widget-footer ttm-bgimage-yes ttm-bg clearfix">
    <div class="ttm-row-wrapper-bg-layer ttm-bg-layer"></div>
    
    <!-- Contact Details -->
    <div class="second-footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 widget-area">
                    <div class="widget widget_text clearfix">
                        <h3 class="widget-title">About Us</h3>
                        <div class="textwidget widget-text">
                            <p>At MAA VAISHNAVI DIAGNOSTIC CENTRE, we are committed to providing high-quality diagnostic services that you can trust.</p>    
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 widget-area">
                    <div class="widget widget_nav_menu clearfix">
                       <h3 class="widget-title">Useful Links</h3>
                        <ul id="menu-footer-service-link" class="menu">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.php">About</a></li>
                            <!--<li><a href="<//?= SITE_PATH;?>neurotherapy.php">Neurotherapy</a></li>-->
                            <li><a href="packages.php">Packages</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 widget-area">
                    <div class="widget widget_nav_menu clearfix">
                       <h3 class="widget-title">Aarogya Packages</h3>
                       <ul id="menu-footer-quick-link" class="menu">
                           <?php
                           // Assuming you have already connected to the database and fetched $packages 
                           // SQL query to fetch all packages
                           $sql = "SELECT id, title FROM packages";
                           $result = $conn->query($sql);

                           // Check if there are any packages returned
                           if ($result->num_rows > 0) {
                               // Output data of each row
                               while($row = $result->fetch_assoc()) {
                                   // Output each package as a list item with a link
                                   echo '<li><a href="package-info.php?id=' . $row['id'] . '">' . $row['title'] . '</a></li>';
                               }
                           } else {
                               echo "No packages found";
                           }

                           // Close database connection
                           $conn->close();
                           ?>
                       </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 widget-area">
                    <div class="widget widget-timing clearfix">
                        <h3 class="widget-title">Contact Details</h3>
                        <div class="ttm-timelist-block-wrapper">
                          <ul class="contact-info-list">
    <li><i class="fas fa-phone"></i> <a href="tel:8328254551">+91-8328254551</a>, <a href="tel:8096536528">8096536528</a>, <a href="tel:8328254551">8328254551</a></li>
    <li><i class="far fa-envelope"></i> <a href="mailto:maavaishnavi0425@gmail.com">maavaishnavi0425@gmail.com</a></li>
    <li class="text-white"><i class="fa fa-map-marker"></i> H.No. 2-2-185/24/89/B&C/1, Mallikarjuna Nagar, Amberpet, Hyderabad - 13</li>
</ul>
<style>.contact-info-list {
    list-style: none;
    padding: 0;
}

.contact-info-list li {
    margin-bottom: 10px; /* Adjust as needed */
    line-height: 1.6; /* Adjust line height */
}

.contact-info-list li i {
    margin-right: 10px; /* Adjust space between icon and text */
}
</style>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Footer Text -->
    <div class="bottom-footer-text ttm-bgcolor-darkgrey clearfix">
        <div class="container">
            <div class="row copyright">
                <div class="col-md-12">
                    <div class="text-center">
                        <span>Copyright © <script>document.write(new Date().getFullYear());</script> . All Rights Reserved by&nbsp;<a href="#">Maa Vashno Devi Diagnostics Center</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- WhatsApp and Call buttons start -->
<div class="contact-buttons">
    <a href="https://wa.me/918328254551" class="whatsapp-button" target="_blank" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <a href="tel:+918328254551" class="call-button" aria-label="Call">
        <i class="fas fa-phone-alt"></i>
    </a>
</div>
<!-- WhatsApp and Call buttons end -->

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
.contact-buttons {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.contact-buttons a {
    display: inline-block;
    width: 50px;
    height: 50px;
    line-height: 50px;
    border-radius: 50%;
    background-color: #25D366; /* WhatsApp color */
    color: white;
    text-align: center;
    margin: 5px;
    font-size: 24px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s;
}

.contact-buttons a.call-button {
    background-color: #34B7F1; /* Call button color */
}

.contact-buttons a:hover {
    background-color: #128C7E; /* WhatsApp hover color */
}

.contact-buttons a.call-button:hover {
    background-color: #0b7dda; /* Call button hover color */
}

    </style>
<!-- Footer end -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script> 
    <script src="assets/js/jquery.easing.js"></script>    
    <script src="assets/js/jquery-waypoints.js"></script>    
    <script src="assets/js/jquery-validate.js"></script> 
    <script src="assets/js/jquery.prettyPhoto.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/numinate.min.js"></script>
    <script src="assets/js/imagesloaded.min.js"></script>
    <script src="assets/js/jquery-isotope.js"></script>
    <script src="assets/js/jquery.event.move.js"></script>
    <script src="assets/js/jquery.twentytwenty.js"></script>
    <script src="assets/js/main.js"></script>
  