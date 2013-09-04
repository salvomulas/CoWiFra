<!-- Header -->
<header>

    <!-- Navigation -->
    <div class="navbar navbar-inverse navbar-static-top">
        <div class="navbar-inner">
            <div class="container">

                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <a class="brand" href="<?php echo base_url(); ?>">WebRE</a>

                <!-- Hidden at 940px or less -->
                <div class="nav-collapse collapse">
                    <ul class="nav nav-pills pull-right">
                        <li><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li><a href="/help" onclick="window.open(this.href, 'Hilfebereich',
'left=20,top=20,width=500,height=550,toolbar=no,resizable=no,scrollbars=yes'); return false;">Hilfe</a></li>
                        <?php
                        $this->load->view('form_login');
                        ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <!-- Navigation finished -->

</header>
<!-- Header finished -->
