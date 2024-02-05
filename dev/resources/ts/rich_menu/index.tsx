import React from 'react';
import Neko from './Neko';
import RichMenuEdit from './RichMenuEdit';
import List from './List';


const RichMenu = () => {
    return (
        <div>
            <h1>RichMenu Page</h1>
           <Neko />
           <RichMenuEdit />
			<List />
        </div>
    );
}

export default RichMenu;