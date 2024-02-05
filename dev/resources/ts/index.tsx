// ./resources/ts/index.tsx
import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';

import Demo from './demo/index';
import RichMenu from './rich_menu/index';
const App = () => {
console.log('A02');//■■■□□□■■■□□□
    return (
        <Router>
            <Routes>
                <Route path="/demo" element={<Demo />} />
                <Route path="/rich_menu" element={<RichMenu />} />
            </Routes>
        </Router>
    );
}

ReactDOM.render(<App />, document.getElementById('root'));
