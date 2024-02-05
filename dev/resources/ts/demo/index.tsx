import React from 'react';
import ButtonDemo from './ButtonDemo';
import ItemList from './ItemList';
import SpaDemo from './SpaDemo';
import ItemListAdd from './ItemListAdd';


const Demo = () => {
console.log('A1');//■■■□□□■■■□□□
    return (
        <div>
            <h1>Demo Page</h1>
            <ButtonDemo />
			<ItemList />
			<SpaDemo />
			<ItemListAdd />
        </div>
    );
}

export default Demo;