<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row-fluid">
    <div class="span6">
        <?php if(isset($columna1_title)){ ?>
        <h3><?php echo $columna1_title; ?></h3>
        <?php } ?>
        <?php if(isset($columna1_data)){ ?>
        <div class="data"><?php echo $columna1_data; ?></div>
        <?php } ?>
    </div>
    <div class="span6">
        <?php if(isset($columna2_title)){ ?>
        <h3><?php echo $columna2_title; ?></h3>
        <?php } ?>
        <?php if(isset($columna2_data)){ ?>
        <div class="data"><?php echo $columna2_data; ?></div>
        <?php } ?>
    </div>
</div>