This is a Statamic project - https://statamic.dev/

We can create tests but choose not to store them
never execute anything that could drop tables or update the database without permission
never execute a migration without permission

Wrap significant code blocks in a try catch block to ensure we generate handled error messaged and debug logs catch any exceptions - where we can use the reportException() helper to ensure that the exception is logged externally

When it comes to creating the html output we have a high focus on performance, accessibility and using semantic html.

For reusability, we use a single antlers layout (resources/views/layout.antlers.html) which using the statmic page builder loads content partials as required, each partial consists of an antlers.html file (typically in resources/views/partials) with a single html <section> element within as the container for the partial content using appropriate classes on that section to reference the scss file we will create for the partial, the .scss partials live in  (resources/sass/partials) if a partial requires a javascript component then we try to use as much native javascript as possible and those partials live in resources/sass/js

We use vite to build the css and js files and the config file is at resources/sass/partials. Typically each partial (antlers html and js combination) relates to a single stamic yaml fieldset which lives in resources/sass/partials

We want the html and css to be as modular and resuable as possible, so we have a set of global font, global and varaible scss files in the resources/sass fodler, please reuse these where possible and add to them in a consistent style if requried.  The html (antlers) files for the site header and footer, navigation etc already exist and are in resources/views/templates and are called by layout.antlers.htmnl so these shoudl be considered the wrapper around and html partials / sections that we crete.

We create our designs in Figma, there will be a unique page for the mobile design and a unique page for the desktop design.  We want to be mobile first, so build the html (antlers) structure to suit the moile layout and that should be the default scss layout, then enhance through scss media queries to create the desktop look and style our standard media query screen width definitions already exist in resources/sass/_variables.scss so please use these.  Each page will consist of multiple sections so please follow the instructions above when creating these, remember that the documnet will also contain the design header and footer which already exist as do many of the globalal stles

Images should always be in modern formats, again optimised for performance, if we can use SVGs whilst keeping the DOM size small then please do so

Use Backpack’s SweetAlert-style confirmation
