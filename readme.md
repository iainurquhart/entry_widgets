# Entry Widgets for ExpressionEngine 2

![Example Widget Publish Page](http://iain.co.nz/dev/entry_widget.png)

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

NOTE: While the fieldtype is installed, it's used by the Tab file - meaning you should not add the Entry Widget fieldtype to any Channel Field Groups. The nature of Tabs are they add themselves to all channels, and utilise installed fields.

### Configuration

Visit the module interface and add an Area. An area consists of a title and a short name - much like channels do. Create an initial area such as "Sidebar Features" and give it the short name "sidebar".

Then, visit any publish page and you'll see a new "Widgets" tab, with a field labelled "Sidebar Features".

Follow your nose there and review how to add a widget.

#### Code Example

To output your widgets, you can use the following as an example

	{exp:channel:entries limit="1"}

		<h1>{title}</h1>

		{exp:entry_widgets:render area="sidebar" entry_id="{entry_id}"}
			<div class="widgetitywidget {slug}">
				<fieldset>
					<legend>This is a widget</legend>
						{if instance_title}<h3>{instance_title}</h3>{/if}
						<div class="widget-body">
							{body}
						</div>
				</fieldset>
			</div>
		{/exp:entry_widgets:render}

	{/exp:channel:entries}

The {body} variable, will render whatever is defined by the widget's display method. Each widget is located within the 'entry_widget' directory and there are two examples for devs to review.

The [Related Entries](https://github.com/iainurquhart/entry_widgets/tree/master/system/expressionengine/third_party/entry_widgets/entry_widget/related_entries) widget should be a good example of what is possible with widgets, in particular - review the [display.php](https://github.com/iainurquhart/entry_widgets/blob/master/system/expressionengine/third_party/entry_widgets/entry_widget/related_entries/views/display.php) view.

Yep, there's a channel entries tag in there, with widget values as parameters and it renders fine.

Enjoy.
