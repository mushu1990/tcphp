



           
            <?php  
            if($menus){
            foreach ($menus as $k => $r) {  ?>
           <h3 class="f14"><span class="switchs cu on" title="<?php echo $r['name'];?>"></span><?php echo $r['name'];?></h3>
            <ul>
            <?php
              $data = $GLOBALS['menumodel']->getMenusByPid($r['menuid']);
              if($data){
              foreach ($data as $key => $row) {             
              
               ?>
   
                  <li id="_MP<?php echo $key;?>" class="sub_menu">
                    <a href="javascript:_MP('<?php  echo $key;?>','/tcphp/index.php/<?php
                    echo $row['url']; ?>');" hidefocus="true" style="outline:none;" data-url="/tcphp/index.php/<?php
                    echo $row['url']; ?>"><?php
                    echo $row['name'];?></a></li>
                   

          <?php } }else{ ?>
         <li id="_MP<?php echo $k;?>" class="sub_menu">
                    <a href="javascript:_MP('<?php  echo $k;?>','/tcphp/index.php/<?php
                    echo $r['url']; ?>');" hidefocus="true" style="outline:none;" data-url="/tcphp/index.php/<?php
                    echo $r['url']; ?>">
                    <?php echo $r['name'];?></a></li>

            <?php }  echo " </ul>"; } } ?>
              
             
            
          

