(() => {
	BX.namespace("KTeam.SidePanel");

	const profileRegExp = /^\/company\/personal\/user\/\d+\/$/

	BX.KTeam.SidePanel.Slider = function(url, options) {
		BX.SidePanel.Slider.call(this, url, options);
	}

	BX.KTeam.SidePanel.Slider.prototype = Object.create(BX.SidePanel.Slider.prototype);
	BX.KTeam.SidePanel.Slider.prototype.constructor = BX.KTeam.SidePanel.Slider;
	BX.KTeam.SidePanel.Slider.prototype.open = function() {
		let url = new URL(this.url, window.location.origin);
		let allowSlider = BX.KTeam.SidePanel.ALLOW_PROFILE_SLIDER === true

		if (allowSlider || !profileRegExp.test(url.pathname)) {
			return BX.SidePanel.Slider.prototype.open.call(this);
		}

		window.location.href = this.url;
	}

	BX.SidePanel.Manager.registerSliderClass('BX.KTeam.SidePanel.Slider');
})();
