<?php

use Beartropy\Settings\Facades\BeartropySettings;

it('resolves the facade', function () {
    expect(BeartropySettings::getFacadeRoot())->toBeInstanceOf(\Beartropy\Settings\Services\SettingsManager::class);
});
