import React, { useEffect, useState } from 'react';

type HiddenData = {
  key: string;
  // 他の必要な型情報も追加できます
};

const GetJsonTest: React.FC = () => {
  const [data, setData] = useState<HiddenData | null>(null);

  useEffect(() => {
    const rawData = document.getElementById("data_json") as HTMLInputElement;

    if (rawData) {
      const parsedData: HiddenData = JSON.parse(rawData.value);
      setData(parsedData);
    }
  }, []); // このeffectはコンポーネントのマウント時に一度だけ実行されます

  if (!data) return null; // データがまだロードされていないか、データが存在しない場合のハンドリング

  return (
    <div>
      <h1>{data.key}</h1>
      {/* 他のデータの表示も行えます */}
    </div>
  );
};

export default GetJsonTest;