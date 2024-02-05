import React from 'react';

interface Item {
  id: number;
  name: string;
}

const ItemList: React.FC = () => {
  const items: Item[] = [
    { id: 1, name: "アイテム1" },
    { id: 2, name: "アイテム2" },
    { id: 3, name: "アイテム3" },
  ];

  return (
    <div>
      <h1>アイテム一覧の見本</h1>
      <ul>
        {items.map((item) => (
          <li key={item.id}>{item.name}</li>
        ))}
      </ul>
    </div>
  );
};

export default ItemList;