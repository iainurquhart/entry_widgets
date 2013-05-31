# Entry Widgets for ExpressionEngine 2

![Example Widget Publish Page](http://f.cl.ly/items/0J3z3w3n3L013h2c3f32/Image%202013.05.31%2012%3A34%3A51%20PM.png)

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

	{exp:channel:entries limit="1"}

		{exp:entry_widgets:render area="sidebar_features" entry_id="{entry_id}"}		
			{widget_body}
		{/exp:entry_widgets:render}

	{/exp:channel:entries}

Each widget is located within the 'widgets' directory and there are some examples for devs to review.

Enjoy.
