import React, { useState } from 'react';

interface Item {
  id: number;
  name: string;
}

const ItemListAdd: React.FC = () => {
  const [inputValue, setInputValue] = useState<string>('');
  const [items, setItems] = useState<Item[]>([]);

  const handleInputChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setInputValue(event.target.value);
  };

  const handleAddItem = () => {
    if (inputValue.trim() === '') return;

    const newItem: Item = {
      id: new Date().getTime(),
      name: inputValue,
    };

    setItems([...items, newItem]);
    setInputValue('');
  };

  return (
    <div>
      <h1>アイテム一覧・追加</h1>
      <input
        type="text"
        placeholder="アイテム名を入力してください"
        value={inputValue}
        onChange={handleInputChange}
      />
      <button onClick={handleAddItem}>追加</button>
      <ul>
        {items.map((item) => (
          <li key={item.id}>{item.name}</li>
        ))}
      </ul>
    </div>
  );
};

export default ItemListAdd;