<div class="center-block">
    <div class="em-sec">

        <h1>Create Segment</h1>

        <div class="list-view-head">

            yaha main segment dikhaunga
        </div>

<!--p class="">Showing Search Results for: <strong>"bikram yoga"</strong></p-->


        <?php echo $this->Session->flash(); ?>

        <!-- search panel start here -->
        <div class="search-panel">

        </div>
        <div class="clm-wrap">
        </div>
        <script>
            $(document).ready(function() {
                $('.bn-hide-show').click(function() {
                    $('.sp-hide-content').toggleClass('show-hide-panel');
                    $(this).text(($(this).text() == 'Show' ? 'Hide' : 'Show'))

                });

                $('.show-more').click(function() {
                    $('.more-option').toggleClass('show-more-option');
                });

                $('.btn-changeLoc').click(function() {
                    $('.findByNum').css('display', 'block');
                    $('.btn-changeLoc').css('display', 'none');
                });

                $('.ld-view').change(function() {
                    $('.event-container .event-box').toggleClass('event-list-view');
                });
                $('.show-more-vibes').click(function() {
                    $('.vibes-more-option').toggleClass('show-more-option');
                });
                $('.show-more-categories').click(function() {
                    $('.categories-more-option').toggleClass('show-more-option');
                });

            });

            function setLimit(limit) {
                $("#limit").val(limit);
                document.search_form.submit();
            }

            $('#changeList').change(function() {
                // set the window's location property to the value of the option the user has selected
                window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/view/" + $(this).val();
            });


            function addAction(whereToRedirect) {
                var myListId = "<?php echo base64_encode($listdetail["MyList"]["id"]); ?>";
                if (whereToRedirect == 1) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/listuserDetail/" + myListId;
                } else if (whereToRedirect == 2) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/importForList/" + myListId;
                }
            }
            function manageAction(whereToRedirect) {
                var myListId = "<?php echo base64_encode($listdetail["MyList"]["id"]); ?>";
                if (whereToRedirect == 1) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/view/" + myListId;
                } else if (whereToRedirect == 2) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/view/" + myListId;
                } else if (whereToRedirect == 3) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/importForList/" + myListId;
                } else if (whereToRedirect == 4) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/segment/" + myListId;
                } else if (whereToRedirect == 5) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/exportCsv/" + myListId;
                }
            }
            function setting(whereToRedirect) {
                var myListId = "<?php echo base64_encode($listdetail["MyList"]["id"]); ?>";
                if (whereToRedirect == 1) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/add/" + myListId;
                }
            }
        </script>