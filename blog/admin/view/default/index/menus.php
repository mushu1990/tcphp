
            <h3 class="f14"><span class="switchs cu on" title="<?php echo $self;?>"></span><?php echo $self;?></h3>
            <ul>
            <?php  foreach ($menus as $key => $row) {
               ?>

                  <li id="_MP<?php echo $key;?>" class="sub_menu">
                    <a href="javascript:_MP('<?php  echo $key;?>','/tcphp/index.php/<?php
                    echo $row['url']; ?>');" hidefocus="true" style="outline:none;" data-url="/tcphp/index.php/<?php
                    echo $row['url']; ?>"><?php
                    echo $row['name'];?></a></li>

          <?php  } ?>
              
             
            </ul>
          

