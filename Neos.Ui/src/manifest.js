import manifest from '@neos-project/neos-ui-extensibility';

import {Option} from '../lib';

manifest('@sitegeist/papertiger-editors', {}, (globalRegistry) => {
	const editorsRegistry = globalRegistry.get('@sitegeist/inspectorgadget/editors');

	editorsRegistry.set(
		'Sitegeist\\PaperTiger\\Domain\\OptionSpecification',
		Option
	);
});
