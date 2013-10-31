# Entry Widgets for ExpressionEngine 2

![Example Widget Publish Page](http://f.cl.ly/items/0J3z3w3n3L013h2c3f32/Image%202013.05.31%2012%3A34%3A51%20PM.png)

## Overview

Entry Widgets is a sideways port of Phil Sturgeon's recently open sourced Widget Module.

The fundamental difference between the Entry Widgets Module and the Widgets Module is as the name suggests - widgets are associated with regions within entries, rather than just regions.

1. You have widgets:
  Eg, Latest Tweets, Latest News, Featured Products
2. You have Areas & Entries
3. You now have available instances of Widgets (with user defined parameters), within Areas, associated with Entries

The add-on is being developed to fill the need of having user defined parameters for 'features' around a page. For example, Entry Widgets enables you to break down an element like a simple call to action into it's various components:

![Example Call to Action Widget](http://f.cl.ly/items/1V1X3E061d0b2N2e0g40/Image%202013.05.31%201%3A55%3A06%20PM.png)

Advanced usage includes being able to utilize ExpressionEngine tags with widgets to have control over parameters. For example: Show x entries from x channel in x order.

![Example Call to Action Widget](http://f.cl.ly/items/2b353m0G2g1D2A202j1K/Image%202013.05.31%201%3A57%3A49%20PM.png)

You get the idea.

## Usage

Note, if you are using EE < 2.7.2 you'll need to [apply a small fix](https://support.ellislab.com/bugs/detail/19427) to the ExpressionEngine core otherwise you'll get a fatal error on the publish page.

### Installation
Add the files as per any regular EE add-on, and enable the Module &amp; Fieldtype.

### Configuration

Visit the module interface and add an Area. An area consists of a title and a short name - much like channels do. Create an initial area such as "Sidebar Features" and give it the short name "sidebar".

Then, create a custom field and associate the field with the area you created.

#### Code Example

	{exp:channel:entries limit="1"}

		<!-- call the field directly -->
		{my_widget_field}

		<!-- or if you want to loop through, use the alternate module syntax -->
		{exp:entry_widgets:render area="sidebar_features" entry_id="{entry_id}"}		
			{widget_count}
			{total_widget_count}
			{widget_body}
		{/exp:entry_widgets:render}

	{/exp:channel:entries}

Each widget is located within the '/system/expressionengine/third_party/entry_widgets/widgets' directory and there are some examples for devs to review.

 - Call to action (simple set of fields for a call to action element)
 - Fancy List (example of repeating rows and various options/params)
 - HTML Data (simple html textarea)
 - Quote (simple set of fields for pullquotes)

#### Other information

If you're a designer who hasn't written a plugin before, you're not going really going to get a whole lot of benefit from this add-on. The strength of Entry Widgets lies in being able to create custom widgets for projects extremely rapidly. To do this, you're going to have to know a little php and be able to pick apart the examples to get what you need to know.

Enjoy.
