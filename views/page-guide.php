<?php include RPP_PLUGIN_DIR . 'views/inc-page-header.php'; ?>

<div class="rpp-guide" style="font-size:14px;">
	<h1>RevPress Guide</h1>
	<p>
		RevPress is the easy way to add <i>Subscribe with Google</i> to your WordPress site.
	</p>

	<h2><i>Subscribe with Google</i></h2>
	<p>
		<i>Subscribe with Google</i> is a service from Google that allows you to offer subscriptions or request contributions to your site using Google's payment system, Google Pay.
	</p>
	<p>
		For more information, see our <a href="https://rev.press/an-overview-of-subscribe-with-google/?cf=n" target="_blank">overview of <i>Subscribe with Google</i></a>.
	</p>
	<p>
		Please be aware that <i>Subscribe with Google</i> is currently in beta. There may be issues and you may see changes as the product is fully developed.
	</p>
	<p>
		Before using the RevPress plugin to add <i>Subscribe with Google</i> to your site, you will need to contact Google and <a href="https://rev.press/how-to-register-for-subscribe-with-google/?cf=n" target="_blank">request access to <i>Subscribe with Google</i></a>.
	</p>
	<p>
		Once approved, you will see a <i>Subscribe with Google</i> module in your <a href="https://publishercenter.google.com" target="_blank">Publisher Center</a>.
	</p>
	<p>
		After <a href="https://rev.press/how-to-set-up-subscribe-with-google/?cf=n" target="_blank">setting up <i>Subscribe with Google</i></a>, Google will provide code snippet(s) that you enter into the RevPress plugin.
	</p>

	<h2>RevPress</h2>
    
    	<h3 style="font-size:20px;margin-left:15px;">Instructions</h3>

	<p>
		Enter a name for the snippet. You can name a snippet based on the pricing/benefits/productID or anything else that helps you reference the snippet.
	</p>

	<p>
		Click on "Enter snippet from Google", then paste the code snippet you receive from Google into the form and click "import".
	</p>
	<p>
		Next, select which posts or pages you wish the code snippet to be included on.
	</p>
	<p>
		You may do this by selecting to place the snippet on:
	</p>
	<ul>
		<li>the entire site</li>
		<li>on posts in selected categories</li>
		<li>on posts that include specific tags</li>
		<li>or specific pages</li>
	</ul>
	<p>
		Posts and pages that include the snippet will display a <i>Subscribe with Google</i> pop-up prompt on them if the reader is not already a subscriber or contributor.
	</p>
       	<h3 style="font-size:20px;margin-left:15px;">Things to Know</h3>

    <p>
		Adding a name for the snippet is required.
	</p>

    <p>
		The optional Notes section can be used to leave yourself any messages or reminders about the snippet's use.
	</p>
        
    <p>
		The "Display fields" link displays the field values imported from the snippet provided by Google.
	</p>

     <p>
		If you wish to include the same snippet using multiple selection options, for example, using a snippet on posts in specific categories and also on certain pages, simply paste the same snippet into another snippet section with a different selection.
	</p>
     
    <p>
		 By clicking on the "Edit Snippet Priorities" you can reorder which snippet is prioritized if there are conflicting settings.
	</p>
    
     <p>
		 Click on "Preview" to see what the prompt will look like when displayed to your readers. Note: if you are subscribed to your own site through your Google account, you will need to log out of your Google account to see the prompt.
	</p>
    
    <p>
		On the settings page below the snippet sections is an "Excluded Roles" setting. Snippets will not be included on posts and pages visted by the user roles you exclude.  
	</p>
    
     <p>
		If you use a CDN or other caching system, your excluded roles may still see a subscription/contribution prompt if they are on a cached page. 
   	</p> 

    <p>
        If you select "Also bypass Cache for these user roles", the parameter "?cache=skip" will be added to all internal links on the front end of your site for excluded user roles in an attempt to bypass the cache. You may also need to set up a rule in your CDN or caching system to bypass urls that include that parameter.  
	</p>
    
	<h2>RevPress Pro</h2>
	<p>
		RevPress Pro membership is designed for publishers that are serious about earning revenue through <i>Subscribe with Google</i>.
	</p>
	<p>
		As a member, you have full access to our exclusive content that includes tips, advice, and information on growing your <i>Subscribe with Google</i> revenue stream.
	</p>
	<p>
		You'll have complete access to our private member-only forum where members ask questions, share solutions, generate ideas, troubleshoot problems, and help each other learn and grow their earnings.
	</p>
	<p>
		And of course, you'll receive RevPress Pro (coming soon, currently in development). The RevPress Pro plugin includes additional features to strengthen your paywall, maximize your revenue stream, and customize <i>Subscribe with Google</i>.
	</p>
	<p>
		Membership is only $10 per month.
	</p>
	<p>
		Maximize your earnings potential and support the ongoing development of RevPress - <a href="https://rev.press/join/?cf=n" target="_blank">Join today</a>!
	</p>

	<h2>Where to Get Help</h2>
	<p>
		RevPress Pro members can get help with the RevPress plugin or ask questions about <i>Subscribe with Google</i> through the <a href="https://rev.press/forums/?cf=n" target="_blank">RevPress Members-only Private Forum</a>.
	</p>
	<p>
		If you need help with the free RevPress plugin, please use the <a href="https://wordpress.org/support/plugin/revpress" target="_blank">RevPress support forum on WordPress.org</a>. We monitor the forum obsessively and provide excellent support.
	</p>
	<p>
		If you need help specifically related to <i>Subscribe with Google</i> (applying, billing, managing subscribers, payments), you can either ask for help on <a href="https://support.google.com/news/publisher-center/community" target="_blank">Google's Publisher Center Help Forum</a> or contact <a href="https://support.google.com/news/publisher-center/contact/swg_default" target="_blank">Google's official <i>Subscribe with Google</i> Publisher Support</a>.
	</p>
</div>

<?php
	include RPP_PLUGIN_DIR . 'views/inc-page-footer.php';
