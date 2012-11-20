# Entry Widgets for ExpressionEngine 2

![Example Widget Publish Page](http://iain.co.nz/dev/widget_2.png)

NOTE: this add-on is not production ready and is open for developer review/discussion.

## Overview

Entry Widgets is a sideways port of Phil Sturgeon's recently open sourced Widget Module.

The fundamental difference between the Entry Widgets Module and the Widgets Module is as the name suggests - widgets are associated with regions within entries, rather than just regions.

1. You have widgets:
  Eg, Latest Tweets, Latest News, Featured Products
2. You have Areas & Entries
3. You now have available instances of Widgets (with user defined parameters), within Areas, associated with Entries

The add-on is being developed to fill the need of having user defined parameters for 'features' around a page. For example, Entry Widgets enables a publisher to output something like this using the "related entries widget":

  * Add a widget which shows 3 entries from News Channel, give the feature a title of "Recent News".
  * Also show another feature, with 5 entries from Products Channel, give it a title "New Products".

You get the idea.

## Usage

### Installation
Add the files as per any regular EE add-on, and enable the Module &amp; Fieldtype.

### Configuration

Visit the module interface and add an Area. An area consists of a title and a short name - much like channels do. Create an initial area such as "Sidebar Features" and give it the short name "sidebar".

Then, create a custom field and associate the field with the area you created.

#### Code Example

To output your widgets, you can use the following as an example. Note here I'm using Stash, and I'm also embedding content widgets inside a content area via passing by reference. In this case I'm also using Low Replace to strip the 'p' tags from around my widgets also.

	{embed="layouts/master"}

	{exp:channel:entries limit="1"}

		<!-- create the needles for content widgets :) -->
		{exp:entry_widgets:render area="content_widget" entry_id="{entry_id}" parse="inward"}		
			{exp:stash:set name="content_widget:{widget_count}" type="snippet"}		
				{widget_body}
			{/exp:stash:set}
		{/exp:entry_widgets:render}

		<!-- stash the main content, and stip <p> tags from around content widgets -->
		{exp:stash:set name="main_content" parse_tags="yes"}
			<h1>{title}</h1>
			{exp:low_replace find="<p.*?>\n.*?({.*?:[0-9]+}).*?<\/p>" replace="$1" regex="yes"}
				{main_content}
			{/exp:low_replace} 
		{/exp:stash:set}

		<!-- stash a sidebar with some widgets from a single widget area -->
		{exp:stash:set name="sidebar"}
			{exp:entry_widgets:render area="sidebar_features" entry_id="{entry_id}" parse="inward"}		
				{widget_body}
			{/exp:entry_widgets:render}
		{/exp:stash:set}

	{/exp:channel:entries}

And then in layouts/master
	
	...
	<article>
		{exp:stash:get name="main_content" parse_tags="yes"}
	</article>
	...
	<aside>
		{exp:stash:get name="sidebar"}
	</aside>

The {widget_body} variable, will render whatever is defined by the widget's display method. Each widget is located within the 'widgets' directory and there are two examples for devs to review.

The [Related Entries](https://github.com/iainurquhart/entry_widgets/tree/master/system/expressionengine/third_party/entry_widgets/widgets/related_entries) widget should be a good example of what is possible with widgets, in particular - review the [display.php](https://github.com/iainurquhart/entry_widgets/blob/master/system/expressionengine/third_party/entry_widgets/widgets/related_entries/views/display.php) view.

Yep, there's a channel entries tag in there, with widget values as parameters and it renders fine.

Enjoy.
