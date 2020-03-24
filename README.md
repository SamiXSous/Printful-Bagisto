<p align="center">
    <img src="https://www.hustlr.com/wp-content/uploads/2019/02/printful.jpg" alt="Printful Logo" align="center">
</p>


<h1 id="printful-bagisto%28laravel%29-extension">Printful-Bagisto(Laravel) Extension</h1>

<p>This extension was built to connect your Printful store to your bagisto store using the official PHP-wrapper from Printful.</p>

<h2 id="installation">Installation</h2>

<h5 id="1.-to-install-the-extension-for-development-please-make-sure-you-have-bagisto-installed-and-have-made-a-new-directory-in-packages-called-samixsous.">1. To install the extension for development please make sure you have Bagisto installed and have made a new directory in <code>packages</code> called <code>SamiXSous</code>.</h5>

<h5 id="2.-once-in-the-%7Bbagisto-root%7D%2Fpackages%2Fsamixsous%2F-directory-you-may-clone-this-repository.">2. Once in the <code>{bagisto root}/packages/SamiXSous/</code> directory you may clone this repository.</h5>

<h5 id="3.-now-that-you-have-downloaded-the-printful-extension-let%27s-install-it-by-adding-the-following-lines-to-%7Bbagisto-root%7D%2Fconfig%2Fapp%2Fphp-file.">3. Now that you have downloaded the Printful extension let&rsquo;s install it by adding the following lines to <code>{bagisto root}/config/app/php</code> file.</h5>

<p>In the providers array add the following:</p>

<pre><code>SamiXSous\Printful\Providers\PrintfulClientServiceProvider::class</code></pre>


<p>And in the alias array add the following:</p>

<pre><code>&apos;Printful&apos; =&gt; SamiXSous\Printful\Facades\Printful::class</code></pre>


<h5 id="once-these-two-lines-have-been-added-you-should-see-a-printful-tab-in-the-admin-section-of-your-bagisto-store%F0%9F%8E%89%F0%9F%A5%B3%F0%9F%99%8C%F0%9F%8F%BC">Once these two lines have been added you should see a Printful tab in the admin section of your Bagisto store!🎉🥳🙌🏼</h5>

<h3 id="roadmap">Roadmap</h3>

<h6 id="this-extension-is-still-in-development-phase-and-could-use-some-extra-tech-fingers">This extension is still in development phase and could use some extra tech fingers!</h6>

<ul>
<li>[x] UI</li>
<li>[x] Made connection with Printful API</li>
<li>[x] Make a landingpage to insert Printful API key</li>
<li>[x] Insert Printful products to Bagisto DB (Sync)</li>
<li>[ ] Insert Pictures to bagisto</li>
<li>[ ] Handle Error Exceptions</li>
</ul>
