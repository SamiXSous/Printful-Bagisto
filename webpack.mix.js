
const mix  = require("laravel-mix");
require("laravel-mix-merge-manifest");
//
if (mix.inProduction()) {
    var publicPath = "public";
} else {
    var publicPath = "../../../public/vendor/samixsous/printful-bagisto/public";
}

mix.setPublicPath(publicPath).mergeManifest();

mix.disableNotifications();

mix
    .js(__dirname + "/src/Resources/assets/js/app.js", "js/printful.js")
    .copyDirectory(
        __dirname + "/src/Resources/assets/images",
        publicPath + "/images"
    )
    .sass(__dirname + "/src/Resources/assets/sass/menu.scss", "css/printfulMenu.css")
    .sass(__dirname + "/src/Resources/assets/sass/dashboard.scss", "css/printfulDashboard.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}
