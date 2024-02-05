import React, { useState, useEffect } from 'react';
import axios from '../cmn/Spa';

const SpaDemo: React.FC = () => {
	const [data, setData] = useState<any>(null);
	const [errorHtml, setErrorHtml] = useState<string | null>(null);

	useEffect(() => {
		const fetchData = async () => {
			try {

				let postData = {'buta':'豚の高級住宅'};
				const response = await axios.post('demo/spa_demo',postData);

				if(typeof response.data === 'string'){
					setErrorHtml(response.request.responseText);
				}
				setData(response.data);

			} catch (error) {
				console.error('Error fetching data:', error);
			}
		};

		fetchData();
	}, []);

	if (!data) return <p>Loading...</p>;

	return (
		<div>
			<h1>SPA Demo</h1>
		{errorHtml && <div dangerouslySetInnerHTML={{ __html: errorHtml }} />}
			<pre>{JSON.stringify(data, null, 2)}</pre>
		</div>
	);
}

export default SpaDemo;