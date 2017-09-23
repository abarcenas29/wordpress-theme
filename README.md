# Wordpress Boiler Plate

Barebones WP theme that isn't really much of a theme but a bunch of files that let WP see that you have a theme.

# Features

_**Webpack**_

Bringing JS to the modern age (for now). Webpack optimizes your JS / Styles (LESS, Sass, CSS, etc...)

_**BrowserSync**_

Changes in the code will immediately effect on the browser. No more bashing the F5 button anymore.

# Folders

* `common` - This is where `.php` files to be included in the `function.php`.
* `images` - Image asset files to be used in the theme
* `src` - This is where `.js` and styles go.
  * `archive.js` - scripts to be used for archive page.
  * `categories.js` - scripts to be used for categories page.
  * `home.js` - scripts to be used for home page.
  * `main.js` - universal script that operates on the entire site.
  * `singles.js` - scripts to be used for single page.
* `webpack` - Webpack files
  * `webpack.config.js` - self-explaining.
  * `webpack.dlls.js` - modules you want to pre-built
  * `dlls` - DLL and script information. *this is generated*

# Setting Up

## Edit `style.css`

Change the information in the file to reflect your information.

## Check your webpack config

Review the webpack config (in the `webpack` folder) and change the configuration according to your preference.

_**BrowserSync**_

This is helpful for reflecting the changes on the code without refreshing the browser.

_NOTE:_ In line 108, change the `proxy` setting to your domain setup in your nginx and host file.

# Development

To run development, in the theme directory, run `yarn run build` to enable dev. Changes will be watched and updated. If you want to run the script for production, `yarn run build:prod` to build the script and not watch any new changes.
