import React from 'react';
import ReactDOM from 'react-dom';
import SpaDemo from './richmenu/SpaDemo';

const Demo: React.FC = () => {
  return <div>Demo Page!<button type="button" class='btn btn-success'>テスト</button></div>;
};



ReactDOM.render(<SpaDemo />, document.getElementById('spa_richmenu'));

export default Richmenu;