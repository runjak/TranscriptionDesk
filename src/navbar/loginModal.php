<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="loginModalLabel">Log in or register</h4>
            </div>
            <div class="modal-body">
                <h3>Authenticate via…</h3><?php
                    foreach(array(
                        array(
                            'auth' => 'github',
                            'text' => 'GitHub',
                            'img'  => 'GitHubLogo.png'
                        ),
                      //array(
                      //    'auth' => 'facebook',
                      //    'text' => 'facebook',
                      //    'img'  => 'FBLogo.png'
                      //),
                      //array(
                      //    'auth' => 'twitter',
                      //    'text' => 'Twitter',
                      //    'img'  => 'TwitterLogo.png'
                      //)
                    ) as $entry){
                        $auth = $entry['auth'];
                        $text = $entry['text'];
                        $img = $entry['img'];?>
                <a class="btn btn-default loginStrategy" href="auth/<?php echo $auth;?>">
                    <img src="img/<?php echo $img;?>"> <strong><?php echo $text;?></strong>
                </a><?php } ?>
            </div>
        </div>
    </div>
</div>
