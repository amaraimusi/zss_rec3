import React from 'react';
import ReactDOM from 'react-dom';
import ToggleButton from './cmn/ToggleButton';
import ItemList from './demo/ItemList';
import ItemListAdd from './demo/ItemListAdd';
import GetJsonTest from './demo/GetJsonTest';
import SpaDemo from './demo/SpaDemo';

const Demo: React.FC = () => {
  return <div>Demo Page!<button type="button" class='btn btn-success'>テスト</button></div>;
};

ReactDOM.render(<ToggleButton targetId="cat_contents" label="コンテンツ1" />, document.getElementById('toggle-button'));

ReactDOM.render(<ItemList />, document.getElementById('item_list'));

ReactDOM.render(<ItemListAdd />, document.getElementById('item_list_add'));

ReactDOM.render(<GetJsonTest />, document.getElementById('get_json_test'));

ReactDOM.render(<SpaDemo />, document.getElementById('spa_demo'));

export default Demo;