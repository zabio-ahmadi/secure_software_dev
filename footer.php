</div><!--end of col-6 -->
<div class="col-3">
    <?php
    if ($obj->loggedin($obj)) {

        echo '<div class="rsidebar">'

            // <div class="search">
            //     <div class="icon">
            //         <i class="fa-solid fa-magnifying-glass"></i>
            //     </div>
            //     <div class="input_search">
            //         <input type="text">
            //     </div>
            // </div>';
    
            ?>


        <div class="topics">
            <b>Todays Hot topic</b>
        </div>
        <?php echo '
            <div>
                <blockquote class="twitter-tweet">
                    <p lang="fr" dir="ltr">La classe à la française. <a
                            href="https://t.co/jP2cg4sdGk">pic.twitter.com/jP2cg4sdGk</a></p>&mdash; Netflix France
                    (@NetflixFR) <a
                        href="https://twitter.com/NetflixFR/status/1712035635312091499?ref_src=twsrc%5Etfw">October 11,
                        2023</a>
                </blockquote>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
    
        </div><!--end of rsidebar -->';
    }
    ?>
</div><!--end of col-3 -->

</div><!--end of row -->
</div> <!--end of container -->




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</body>

<script src="js/main.js"></script>

</html>