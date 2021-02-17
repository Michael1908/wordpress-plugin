<?php
/**
 * @package Threekit
 */
/*
Plugin Name: Threekit
Plugin URI: https://source360group.com/
Description: Threekit plugin to handle data.
Version: 0.1
Author: Source 360
Author URI: https://source360group.com/
License:
Text Domain:
*/


function threekit_register_post_type() {
     
    // products
    $labels = array( 
        'name' => __( 'Threekit' , 'threekit' ),
        'singular_name' => __( 'Product' , 'threekit' ),
        'add_new' => __( 'New Product' , 'threekit' ),
        'add_new_item' => __( 'Add New Product' , 'threekit' ),
        'edit_item' => __( 'Edit Product' , 'threekit' ),
        'new_item' => __( 'New Product' , 'threekit' ),
        'view_item' => __( 'View Product' , 'threekit' ),
        'search_items' => __( 'Search Products' , 'threekit' ),
        'not_found' =>  __( 'No Products Found' , 'threekit' ),
        'not_found_in_trash' => __( 'No Products found in Trash' , 'threekit' ),
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => true,
        'public' => true,
        'hierarchical' => false,
        'supports' => array(
            'title', 
            'editor', 
            'excerpt', 
            'custom-fields', 
            'thumbnail',
            'page-attributes'
        ),
        'rewrite'   => array( 'slug' => 'products' ),
        'show_in_rest' => true
 
    );
    register_post_type( 'threekit_product', $args );
         
}

function threekit_product_styles() {
    // wp_enqueue_style( 'products',  plugin_dir_url( __FILE__ ) . ‘/css/threekit.css’ );                      
}

function threekit_player(){
    return "<div>Hello threekit</div>
    <div id='threekit-player' style='height: 500px'></div>";
};


// add_action( 'init', 'threekit_register_post_typ' );
add_action( 'wp_enqueue_scripts', 'threekit_product_styles' );
add_shortcode('threekit_player','threekit_player');

add_action('wp_footer', 'tutsplus_add_script_wp_footer');
function tutsplus_add_script_wp_footer() {
    ?>
      <script>

        console.log("I'm an inline script tag added to the footer.");
        var tkPlayer = document.getElementById("threekit-player");
        const initialSettings = {
          el: tkPlayer,
          authToken: 'd702bc46-c483-4665-9e62-287916a3d14a',
          assetId: '540bd59c-11a5-41b5-aa9a-4ead3858fd4e',
          orgId: '14e9f2e4-d0b6-4066-81ad-480672669316',
        };

        let player;
        let attributes;
        // A $( document ).ready() block
        //here start customized UI
        $(document).ready(function () {
          console.log("Document Ready");


        });

        initializePlayer(initialSettings);


        /******************************************/
        /* Function to start building the UI      */
        /******************************************/
        function buildCustomUI() {
          getAttributesData().then((result) => {
            attributes = result;
            const firstAttributeValues = attributes[Object.keys(attributes)[0]];
            let swatchItemImg = '';
            let itemMenu = '';
            let keyAttributes = Object.keys(attributes);
        
        
          });
        }

        /******************************************/
        /* Function to Get Attributes 3Kit Player */
        /******************************************/
        async function getAttributesData() {
          if (!window.threekitApi || !window.threekitApi.configurator)
            throw new Error('threekitApi not setup');
          const output = {};
          const attributes = await window.threekitApi.player.configurator.getVisibleAttributes();
          console.log('attributes => ', attributes);
          await Promise.all(
            attributes.map(async (attr) => {
              if (!['Asset', 'String'].includes(attr.type)) return output;
              output[attr.name] = {
                id: attr.name.toLowerCase().replace(' ', '-'),
                type: attr.type,
                label: attr.name
              };
              if (attr.type === 'String') {
                console.log('attr.values = >', attr.values);
                output[attr.name].values = await Promise.all(
                  attr.values.map(async (val) => {
                    const { name, configurator } = await window.threekitApi.api.scene.fetch(initialSettings.assetId).then(() => window.threekitApi.api.scene.get({ id: initialSettings.assetId }));
                    const metadata = configurator.metadata;
                    const imagesUrl = metadata.find(metadataItem => metadataItem.name === attr.name + '_THUMBIMAGEURL') ? JSON.parse(metadata.find(metadataItem => metadataItem.name === attr.name + '_THUMBIMAGEURL').defaultValue) : '';
                    const typeTitle = metadata.find(metadataItem => metadataItem.name === attr.name + '_TITLE') ? JSON.parse(metadata.find(metadataItem => metadataItem.name === attr.name + '_TITLE').defaultValue) : '';
                    const typeDescription = metadata.find(metadataItem => metadataItem.name === attr.name + '_DESCRIPTION') ? JSON.parse(metadata.find(metadataItem => metadataItem.name === attr.name + '_DESCRIPTION').defaultValue) : '';
                    return {
                      id: val.toLowerCase().replace(' ', '-'),
                      value: val,
                      label: val,
                      image: imagesUrl?.[val] !== undefined ? imagesUrl[val] : '',
                      typeTitle: typeTitle?.[val] !== undefined ? typeTitle[val] : '',
                      typeDescription: typeDescription?.[val] !== undefined ? typeDescription[val] : '',
                    }
                  }));
              }
              else if (attr.type === 'Asset') {
                output[attr.name].values = await Promise.all(
                  attr.values.map(async (val) => {
                    const { name, configurator } = await window.threekitApi.api.scene.fetch(val.assetId).then(() => window.threekitApi.api.scene.get({ id: val.assetId }));
                    const metadata = configurator.metadata;
                    const imangeUrl = metadata.find(metadataItem => metadataItem.name === "thumbImageUrl") ? JSON.parse(metadata.find(metadataItem => metadataItem.name === "thumbImageUrl").defaultValue) : '';
                    const typeTitle = metadata.find(metadataItem => metadataItem.name === "typeTitle") ? JSON.parse(metadata.find(metadataItem => metadataItem.name === "typeTitle").defaultValue) : '';
                    const typeDescription = metadata.find(metadataItem => metadataItem.name === "typeDescription") ? JSON.parse(metadata.find(metadataItem => metadataItem.name === "typeDescription").defaultValue) : '';
                    if (name) {
                      return {
                        id: name.toLowerCase().replace(' ', '-'),
                        assetId: val.assetId,
                        value: name,
                        label: name,
                        image: imangeUrl !== '' ? imangeUrl : '',
                        typeTitle: typeTitle !== '' ? typeTitle : '',
                        typeDescription: typeDescription !== '' ? typeDescription : ''
                      }
                    }
                  }))
              } else {
                output[attr.name].values = await Promise.all(
                  attr.values.map(async (val) => {
                    if (!val || !val.assetId) return;
                    const opt = {
                      id: val.name.toLowerCase().replace(' ', '-'),
                      value: val.assetId,
                      label: val.name
                    };

                    const dependency = new RegExp(/^_dependency/);
                    const re = new RegExp(/^_/);
                    const sceneApi = window.threekitApi.api.scene;
                    let node = sceneApi.get({ id: val.assetId });
                    if (!node) {
                      await sceneApi.fetch(val.assetId);
                      node = sceneApi.get({ id: val.assetId });
                    }
                    node.configurator.metadata.forEach((metadata) => {
                      if (
                        dependency.test(metadata.name) &&
                        JSON.parse(metadata.defaultValue)
                      )
                        opt.dependencies = opt.dependencies
                          ? opt.dependencies.push(JSON.parse(metadata.defaultValue))
                          : [JSON.parse(metadata.defaultValue)];
                      else if (metadata.name in metadataMap)
                        opt[metadataMap[metadata.name]] = metadata.defaultValue;
                      else if (re.test(metadata.name))
                        opt[metadata.name.substring(1, metadata.name.length)] =
                          metadata.defaultValue;
                    });
                    return opt;
                  })
                );
              }
            })
          );
          console.log('output => ', output);
          return output;
        };
        /**************************************/
        /* Function to Initialize 3Kit Player */
        /**************************************/
        function initializePlayer() {
          var tkPlayer = document.getElementById("threekit-player");
          return new Promise(async (resolve, reject) => {
            if (!window.threekitPlayer)
              reject('window.threekitPlayer object is missing threekitPlayer api');
            if (!initialSettings.authToken)
              reject('intialSettings object is missing the authToken');
            if (!initialSettings.assetId)
              reject('intialSettings object is missing the assetId');
            if (!initialSettings.orgId)
              reject('intialSettings object is missing the orgId');
            if (!initialSettings.el) reject('intialSettings object is missing the el');

            const threekitConfg = initialSettings;

            const threekitApi = await window.threekitPlayer({
              //   authToken: '01234567-89ab-cdef-0123-456789abcdef',
              //   el: document.getElementById('player-root'),
              //   stageId: '27b9cd7e-2bb2-4a18-b788-160743eb4b33',
              //   assetId: 'e12a45f7-8b39-cd06-e12a-45f78b39cd06',
              //   showConfigurator: true,
              //   showAR: true,
              //   initialConfiguration: {},
              //   showShare: true,
              ...threekitConfg,
            });
            if (!window.threekitPlayer) reject('Error initializing player');

            /***** API SETUP START ***************************************************************/
            //  Enables access to the threekit store api
            threekitApi.enableApi('store');

            //  Enables access to the threekit player api
            player = threekitApi.enableApi('player');

            window.threekitApi = {
              api: threekitApi,
              player,
              configurator: player.getConfigurator(),
            };
            threekitApi.on(threekitApi.scene.PHASES.PRELOADED, () => {
              //    Assigns default configurator to window object
              window.threekitApi.configurator = threekitApi.player.getConfigurator();
              initialSettings.onPreload
                ? initialSettings.onPreload()
                : buildCustomUI();
            });
            resolve(true);
          });
        };
        /******************************************/
        /* Function to Set Configuration 3Kit Player */
        /******************************************/
        async function setConfiguration(config) {
          if (!config || !window.threekitApi) return;

          if (typeof config !== 'object') return;

          const updateConfig = Object.entries(config).reduce(
            (output, [attribute, value]) =>
              Object.assign(output, {
                [attribute]: isUuid(value) ? { assetId: value } : value,
              }),
            {}
          );

          let keys = Object.keys(config);
          // if (!keys[0].includes('Lettering')) {
          //   document.getElementById('loader-container').style.display = 'block';
          // }
          console.log('updateConfig', updateConfig);
          await window.threekitApi.configurator.setConfiguration(updateConfig);
        };
        /******************************************/
        /* Function to Set Configuration 3Kit Player */
        /******************************************/
        function getConfiguration() {
          if (!window.threekitApi) return;
          return window.threekitApi.configurator.getConfiguration();
        };

        /******************************************/
        /* Function to valid GUID                 */
        /******************************************/
        function isUuid(str) {
          // Regex to check valid GUID (Globally Unique Identifier).
          const regex = /^[{]?[0-9a-fA-F]{8}-([0-9a-fA-F]{4}-){3}[0-9a-fA-F]{12}[}]?$/;

          // Return true if the GUID (Globally Unique Identifier)
          // matched the ReGex
          if (str.match(regex)) {
            return true;
          }
          else {
            return false;
          }
        }

      </script>
    <?php
}