Roundcube Webmail Skin "Elastic"
================================

This skin package contains a theme for the Roundcube Webmail
software. It can be used, modified and redistributed according to
the terms described in the LICENSE section.

For information about building or modifying Roundcube skins please visit
https://github.com/roundcube/roundcubemail/wiki/Skins


LICENSE
-------

The contents of this folder are subject to the Creative Commons
Attribution-ShareAlike License. It is allowed to copy, distribute,
transmit and to adapt the work by keeping credits to the original
authors in the README.md file.
See http://creativecommons.org/licenses/by-sa/3.0/ for details.

This folder also contains code licensed separately:
- Bootstrap Framework 4 from https://github.com/twbs/bootstrap
- FontAwesome 5 fonts from https://fontawesome.com/
- Roboto font from https://google-webfonts-helper.herokuapp.com/fonts/roboto?subsets=cyrillic,latin-ext,cyrillic-ext,latin,greek,greek-ext


INSTALLATION
------------

All styles are written using LESS syntax. Thus it needs to be compiled
using the `lessc` command line tool (that you can install with `npm install`).
```
    $ npx lessc --clean-css="--s1 --advanced" styles/styles.less > styles/styles.min.css
    $ npx lessc --clean-css="--s1 --advanced" styles/print.less > styles/print.min.css
    $ npx lessc --clean-css="--s1 --advanced" styles/embed.less > styles/embed.min.css
```

References to image files from the included CSS files can be appended
with cache-buster marks to avoid browser caching issues after updating.

Run `bin/updatecss.sh --dir skins/elastic` before packaging the skin
or after installing it on the destination system.


FOR DEVELOPERS
--------------

- Supported browsers: Last 2 versions of Edge/Chrome/Firefox/Safari.

- Skin color palette changes and other css modifications can be done
  via _styles.less and _variables.less files. Where you can overwrite all
  variables and add custom styles.

- Minimum supported screen width is 240px (note that even if the device screen
  resolution is e.g.320x372 changing the text size in device settings will reduce
  the resolution)

- Every page (which is not a frame) has following required structure:
```
    <body>
        <div id="layout">
            <div id="layout-menu"></div>
            <div id="layout-sidebar"></div>
            <div id="layout-list"></div>
            <div id="layout-content"></div>
        </div>
    </body>
```
  where `#layout-sidebar` and `#layout-list` are optional. Which element of the `#layout` will be displayed
  as a main view on mobile devices can be defined by adding `selected` class to it.

- The `<html>` element will receive special classes that will be updated on resize
  or orientation change:
    - `touch`: A touch device, screen width <= 1024px,
    - `layout-large`: Screen width > 1200px,
    - `layout-normal`: Screen width <= 1200px and >= 768px,
    - `layout-small`: Screen width < 768px and > 480px,
    - `layout-phone`: Screen width <= 480px.

  Frames will have the same classes applied as their parent windows.

- Every button, that is not <button> nor <input> should have inner <span class="inner"> element
  for the button label.

- Every iframe need to be wrapped by an element with `iframe-wrapper` class. It is
  required to have proper scrolling on mobile devices.

- Special attributes:
    - `data-hidden`: Makes a menu entry/button hidden on specified screen sizes.
      Can be used for example for functionality not implemented or that has no sense
      on phones or touch devices. Contains a comma-separated list following values:
      `large` (width > 1200px), `big` (width > 768px), `small` (width =< 768px),
      `phone` (width =< 480px), `lbs` (width > 480px).

    - `data-content-button`: Makes the action button with this attribute to be copied
      to the content frame header on small/phone screens.

- Special URLs:
    In phone mode we display Prev/Next navigation buttons below the content preview
    frame. We do this e.g. for mail preview or contact preview. Plugins should use
    _action=add* or _action=create* or _nav=hide in the frame URL if the navigation
    should be hidden, which is the case when you create a content object.

- Dark mode:
    In dark mode html element will have `dark-mode` class assigned.
    It is possible to disable dark mode using less variable `@dark-mode-enabled`.
    Option `dark_mode_support` in meta.json controls addition of the mode switch in the menu.


KNOWN ISSUES
------------

1. There are known issues with running less in Roundcube devel_mode:
    - Dialogs executed on page load are displayed out of screen.
    - CodeMirror editor (in managesieve raw script editing mode) is broken.
