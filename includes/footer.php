<footer>
  <div class="auto-container">
  <!--Widgets Section-->
  <div class="widgets-section">
    <div class="row clearfix d-flex">
          <div class="footer-column col-lg-4 col-md-4 col-sm-12 order-2 order-lg-1">
            <div class="footer-widget newsletter-widget">
              <h2  style="padding-left:50px;">Contact Info</h2>
      <br>
              <a target="_blank"  href="tel:+918882655840"><i id="icon-contact" class="fa fa-phone-square" aria-hidden="true"></i>
                  <p style="color:#777777;">8882655840</p></a>
              <a target="_blank" href="mailto:support@lions3234d2.com"><i id="icon-contact" class="fa fa-envelope" aria-hidden="true"></i>
                <p style="color:#777777;">support@lions3234d2.com</p>  </a>
             
            </div>

          </div>


          <div class="footer-column col-lg-4 col-md-4 col-sm-12 order-1 order-lg-2">
            <div class="footer-widget logo-widget">
              <div class="logo">
                <a href="index.php"><img src="./img/logo2.png" alt=""></a>
              </div>
                <div class="icons">
              <a href="https://twitter.com/lions3234"><i class="fa fa-twitter" aria-hidden="true"></i></a>
              <a href="https://www.instagram.com/lions3234d2/"><i class="fa fa-instagram"
                      aria-hidden="true"></i></a>
              <a href="https://www.facebook.com/groups/1155405058140574"><i class="fa fa-facebook"
                      aria-hidden="true"></i></a>
                    </div>
            </div>
          </div>

          <!--Footer Column-->


          <div class="footer-column col-lg-4 col-md-4 col-sm-12 order-3 order-lg-3">
          <div class="footer-widget links-widget" style="padding-left: 60px;">
            <h2>Quick links</h2>
            <div class="widget-content">
              <ul>
                <li><a href="./index.php">Home</a></li>
                <li><a href="./member/member-login.php">Member</a></li>
                <li><a href="./gallery.php">Gallery</a></li>
                <li><a href="./event.php">Events</a></li>
                <li><a href="./activities.php">Activities</a></li>
                <li><a href="./news.php">News</a></li>
              </ul>
            </div>
          </div>
          </div>
          <div class="big-column">
        <!-- </div> -->
      </div>

    </div>
  </div>
</div>
    <hr> 
    <p class="text-center">Copyright <?php echo date("Y"); ?> ©  All rights reserved. |
      Designe & Developed by <a href="./super/home.php"><i class="fa fa-cube" aria-hidden="true"></i></a><a id="hspm" href="https://www.hspmsolutions.com/"> <b> HSPM Solutions LLP.</b></a>
    </p>

    <style>
    .contact-btn {
        display: flex;
        height: 4rem;
        width: 4rem;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        background: #084f9d;
        color: whitesmoke;
        font-size: 3rem;
        padding: 0.25rem;
        text-align: center;
        -webkit-box-shadow: 0.1rem 0.3rem 0.3rem rgba(223, 177, 98, 0.5);
        box-shadow: 0.1rem 0.3rem 0.3rem rgba(223, 177, 98, 0.5);
        position: fixed;
        right: 1rem;
        bottom: 2rem;
        cursor: pointer;
        z-index: 999;
        -webkit-transition: 0.5s ease-in-out all;
        transition: 0.5s ease-in-out all;
    }

    .contact-btn:hover {
        -webkit-box-shadow: 0.1rem 0.1rem 0.1rem rgba(223, 177, 98, 0.7), -0.1rem -0.1rem 0.1rem rgba(223, 177, 98, 0.7);
        box-shadow: 0.1rem 0.1rem 0.1rem rgba(16, 110, 234, 0.7), -0.1rem -0.1rem 0.1rem rgba(16, 110, 234, 0.7);
    }

    .all-btns {
        display: none;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        position: fixed;
        right: 1rem;
        bottom: 2rem;
        cursor: pointer;
        z-index: 999;
        -webkit-transition: 0.5s ease-in-out all;
        transition: 0.5s ease-in-out all;
    }

    .all-btns .contact-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 3.5rem;
        width: 3.5rem;
        font-size: 2.25rem;
        border-radius: 50%;
        color: whitesmoke;
        text-align: center;
        margin: 0.5rem auto;
        -webkit-transition: 0.5s ease-in-out all;
        transition: 0.5s ease-in-out all;
        -webkit-box-shadow: 0.1rem 0.3rem 0.3rem rgba(0, 0, 0, 0.5), -0.1rem -0.3rem 0.3rem rgba(0, 0, 0, 0.5);
        box-shadow: 0.1rem 0.3rem 0.3rem rgba(0, 0, 0, 0.5), -0.1rem -0.3rem 0.3rem rgba(0, 0, 0, 0.5);
    }

    .all-btns .contact-logo a {
        display: block;
        color: whitesmoke;
        text-align: center;
        margin: auto;
        text-decoration: none;
    }

    .all-btns .contact-logo:nth-of-type(1) {
        background: #009688;
    }

    .all-btns .contact-logo:nth-of-type(1):hover {
        -webkit-box-shadow: 0.1rem 0.1rem 0.1rem rgba(0, 0, 0, 0.7), -0.1rem -0.1rem 0.1rem rgba(0, 0, 0, 0.7);
        box-shadow: 0.1rem 0.1rem 0.1rem rgba(0, 0, 0, 0.7), -0.1rem -0.1rem 0.1rem rgba(0, 0, 0, 0.7);
    }

    .all-btns .contact-logo:nth-of-type(2) {
        background: dodgerblue;
    }

    .all-btns .contact-logo:nth-of-type(2):hover {
        -webkit-box-shadow: 0.1rem 0.1rem 0.1rem rgba(0, 0, 0, 0.7), -0.1rem -0.1rem 0.1rem rgba(0, 0, 0, 0.7);
        box-shadow: 0.1rem 0.1rem 0.1rem rgba(0, 0, 0, 0.7), -0.1rem -0.1rem 0.1rem rgba(0, 0, 0, 0.7);
    }

    .all-btns .contact-logo:nth-of-type(3) {
        background: #e41749;
    }

    .all-btns .contact-logo:nth-of-type(3):hover {
        -webkit-box-shadow: 0.1rem 0.1rem 0.1rem rgba(0, 0, 0, 0.7), -0.1rem -0.1rem 0.1rem rgba(0, 0, 0, 0.7);
        box-shadow: 0.1rem 0.1rem 0.1rem rgba(0, 0, 0, 0.7), -0.1rem -0.1rem 0.1rem rgba(0, 0, 0, 0.7);
    }

    .all-btns .contact-logo:nth-of-type(4) {
        background: #fc5185;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .all-btns .contact-logo:nth-of-type(4):hover {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
    </style>
    <div class="contact-btn" id="contact-btn">
        <div class="contact-logo">
            <i class="fa fa-comments" aria-hidden="true"></i>
        </div>
    </div>

    <div class="all-btns" id="all-btns">
        <div class="contact-logo">
            <a target="_blank" href="https://api.whatsapp.com/send?phone=+919822306359"><i class="fa fa-whatsapp"
                    aria-hidden="true"></i></a>
        </div>
        <div class="contact-logo">
            <a target="_blank" href="tel:+918882655840"><i class="fa fa-phone" aria-hidden="true"></i></a>
        </div>

        <div class="contact-logo" id="close-btn">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
    </div>
    <script>
    const contactBtn = document.querySelector('#contact-btn');
    const allBtns = document.querySelector('#all-btns');
    const closeBtn = document.querySelector('#close-btn');

    contactBtn.addEventListener('click', () => {
        allBtns.style.display = "flex";
        contactBtn.style.display = "none";
    });

    closeBtn.addEventListener('click', () => {
        allBtns.style.display = "none";
        contactBtn.style.display = "flex";
    });
    </script>
</footer>
<script src="links/js/main.js"></script>
