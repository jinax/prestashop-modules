<!-- Block mymodule -->

<div id="mymodule_block_home" class="block">
  <h4>{l s='MyModule' mod='mymodule'}</h4>
    <div class="block_content">
        <div class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            
          {foreach from=$array_prod item=elem}
            <div class="swiper-slide">

            {assign var=imagen value=Image::getCover($elem['id_product'])}
          
            {$imagePath=$link->getImageLink($elem.link_rewrite, $imagen['id_image'], 'home_default')} 
             <img src="http://{$imagePath}">
           
            </div>
          {/foreach}
        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <!-- If we need scrollbar -->
        <div class="swiper-scrollbar"></div>
    </div>
 
    <ul>
      <li><a href="{$my_module_link}" title="Click this link">Click me!</a></li>
    </ul>
  </div>
</div>

<!-- /Block mymodule -->