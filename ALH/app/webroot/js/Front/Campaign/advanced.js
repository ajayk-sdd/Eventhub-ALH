if (typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};

RedactorPlugins.advanced = {

	init: function()
	{
		this.buttonAdd('advanced', 'Advanced', this.insertAdvancedHtml);

		// Make button as Font Awesome icon
		this.buttonAwesome('advanced', 'fa-bullhorn');
	},
	insertAdvancedHtml: function()
	{
		this.insertHtml('<b>It\'s awesome!</b> ');
	}
}