import React, { useState } from 'react';
import { Button } from 'antd';

const ButtonDemo = () => {
    const [message, setMessage] = useState('');

    const handleClick = () => {
        setMessage('Hello World!');
    };

    return (
        <div>
            <Button type="primary" onClick={handleClick}>Click Me</Button>
            <p>{message}</p>
        </div>
    );
};

export default ButtonDemo;