<!-- Block mymodule -->

{* <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">

<script src="https://unpkg.com/swiper/js/swiper.min.js"></script> *}

<div id="mymodule_block_home" class="block">
  <h4>{l s='Welcome!' mod='mymodule'}</h4>
  <div class="block_content">
    <p>Hello,
           {if isset($my_module_name) && $my_module_name}
               {$my_module_name}
           {else}
               World
           {/if}
           !
    </p>
    <ul>
      <li><a href="{$my_module_link}" title="Click this link">Click me!</a></li>
    </ul>
  </div>
</div>

<!-- /Block mymodule -->