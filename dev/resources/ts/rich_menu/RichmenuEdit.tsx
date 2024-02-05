import React, { useState } from 'react';
import axios from '../cmn/Spa';
import { Button } from 'antd';

const RichMenuEdit = () => {
    const [message, setMessage] = useState('');
	const [data, setData] = useState<any>(null);
	const [errorHtml, setErrorHtml] = useState<string | null>(null);

    const handleClick = () => {
	
		const fetchData = async () => {
			try {

				let postData = {'buta':'豚の高級住宅'};
				const response = await axios.post('rich_menu/spa_demo',postData);

				if(typeof response.data === 'string'){
					setErrorHtml(response.request.responseText);
				}
				setData(response.data);

			} catch (error) {
				console.error('Error fetching data:', error);
			}
		};

		fetchData();
        setMessage('Hello World!');
    };

    return (
        <div>
            <Button type="primary" onClick={handleClick}>Click Me</Button>
            <p>{message}</p>
		{errorHtml && <div dangerouslySetInnerHTML={{ __html: errorHtml }} />}
			<pre>{JSON.stringify(data, null, 2)}</pre>
        </div>
    );
};

export default RichMenuEdit;