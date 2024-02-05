import React, { useState, useEffect } from 'react';
import { Form, Input, Button, Table } from 'antd';
import axios from '../cmn/Spa';

type RichMenu = {
  id: string;
  name: string;
  segment: string;
  start_time: Date;
  end_time: Date;
  deliveries_count: number;
  // その他のフィールド
};

const List = () => {
  const [data, setData] = useState<RichMenu[]>([]);
  const [loading, setLoading] = useState<boolean>(false);
  const [pagination, setPagination] = useState({ current: 1, pageSize: 5, total: 0 });

  // データの初期読み込み
  useEffect(() => {
    fetchData({ pagination });
  }, []);

  // データ取得関数
  const fetchData = async (params = {}) => {
    setLoading(true);
    try {
      const response = await axios.post('rich_menu/get_list_spa', params);

      setData(response.data.data);
      setPagination({ ...params.pagination, total: response.data.total });
      setLoading(false);
    } catch (error) {
      setLoading(false);
      // エラー処理
    }
  };

  // 検索処理
  const handleSearch = (values) => {
    fetchData({
      ...values,
      pagination: { ...pagination, current: 1 },
    });
  };

  // テーブル変更処理（ページネーション、ソート）
  const handleTableChange = (newPagination, filters, sorter) => {
    fetchData({
      sortField: sorter.field,
      sortOrder: sorter.order,
      pagination: newPagination,
      ...filters,
    });
  };

  // 表示列の定義
  const columns = [
    {
      title: 'リッチメニュー名',
      dataIndex: 'name',
      sorter: true,
    },
    {
      title: 'セグメント',
      dataIndex: 'segment',
      sorter: true,
    },
    {
      title: '配信設定数',
      dataIndex: 'deliveries_count',
    },
    // その他の列
  ];

  return (
    <div>
      <div>TEST10</div>
      <Form onFinish={handleSearch} layout="inline">
        <Form.Item name="name" style={{ width: '20%' }}>
          <Input placeholder="リッチメニュー名" />
        </Form.Item>
        <Form.Item name="segment" style={{ width: '20%' }}>
          <Input placeholder="セグメント" />
        </Form.Item>
        <Form.Item>
          <Button type="primary" htmlType="submit">
            検索
          </Button>
        </Form.Item>
      </Form>
      <Table 
        columns={columns}
        dataSource={data}
        rowKey="id"
        pagination={{
          ...pagination,
          position: ['bottomCenter']
        }}
        loading={loading}
        onChange={handleTableChange}
      />
    </div>
  );
};

export default List;