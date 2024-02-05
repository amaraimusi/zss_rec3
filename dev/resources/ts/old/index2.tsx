import React from 'react';
import ReactDOM from 'react-dom';
import {
  BrowserRouter as Router,
  Route,
  Routes
} from 'react-router-dom';


import Demo from './demo';
import Richmenu from './richmenu';

const App: React.FC = () => {
  return (
    <Router>
      <Routes>
        <Route path="/demo" element={<Demo />} />
        <Route path="/richmenu" element={<Richmenu />} />
        {/* 他のルートもこちらに追加可能 */}
      </Routes>
    </Router>
  );
};

export default App;

//ReactDOM.render(<App />, document.getElementById('react_app'));