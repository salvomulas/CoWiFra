        <div id="bc">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <a href="/home"><i class="icon-home"></i></a> /
                            <?php
                            $path = '';
                            if (isset($breadcrumbs)) {
                                foreach($breadcrumbs as $step) {
                                    $path .= '<a href="' . $step['url'] . '">' . htmlspecialchars($step['name']) . '</a> / ';
                                }
                                echo substr($path, 0, strlen($path)-3); // Strip last slash
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row center">
                <div class="span12">
                    <h1 class="text-center"><?php echo $page_title; ?></h1>
                </div>
            </div>

            <?php
            if(isset($success_messages)) {
                echo '<div class="alert alert-success">';
                foreach ($success_messages as $success_message) {
                    echo '<p>' . $success_message . '</p>';
                }
                echo '</div>';
            }
            ?>

            <?php
            if(isset($info_messages)) {
                echo '<div class="alert alert-info">';
                foreach ($info_messages as $info_message) {
                    echo '<p>' . $info_message . '</p>';
                }
                echo '</div>';
            }
            ?>

            <?php
            if(isset($error_messages)) {
                echo '<div class="alert alert-error">';
                foreach ($error_messages as $error_message) {
                    echo '<p>' . $error_message . '</p>';
                }
                echo '</div>';                
            }
            ?>

            <hr />            
        </div>
    </body>
</html>
