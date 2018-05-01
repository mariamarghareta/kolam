create or replace view pembelian as 
select distinct 'l' as tipe, id, dt, '' as ref_id,  name, '' as satuan, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan, deleted
    from beli_lain
    UNION
    select distinct 'o' as tipe, beli_obat.id, dt, obat_id as ref_id,  obat.name, obat.satuan, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan, beli_obat.deleted
    from beli_obat
    left join obat on obat.id = beli_obat.obat_id
    UNION
    select distinct 'p' as tipe, beli_pakan.id, dt, pakan_id as ref_id,  pakan.name, pakan.satuan, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan, beli_pakan.deleted
    from beli_pakan
    left join pakan on pakan.id = beli_pakan.pakan_id

------------------------------
--view untuk real stok pakan--
------------------------------
create or replace view pakan_real_stok as 
SELECT pakan.id as pakan_id, adj.stok as stok_adj, beli_after_adj.total_isi as total_pembelian_after_adj, tot_beli.total as total_pembelian_pakan
, mon.total as monitoring_after_adj, tot_mon.total as total_monitoring_pakan,
ifnull(adj.stok,0) + (case when beli_after_adj.pakan_id is null then ifnull(tot_beli.total,0) else ifnull(beli_after_adj.total_isi,0) end) - (case when mon.pakan_id is null then ifnull(tot_mon.total,0) else ifnull(mon.total,0) end) as final_stok
from pakan
left join adj on adj.pakan_id = pakan.id
left join beli_after_adj on beli_after_adj.pakan_id = pakan.id
left join tot_beli on tot_beli.pakan_id = pakan.id
left join mon on mon.pakan_id = pakan.id
left join tot_mon on tot_mon.pakan_id = pakan.id

create or replace view max_adj as
SELECT max(id) as id, pakan_id
from pakan_inventory_adj adj
group by pakan_id

create or replace view adj as
select adj.pakan_id, adj.write_time, adj.stok
from max_adj
left join pakan_inventory_adj adj on adj.id = max_adj.id
group by adj.pakan_id, adj.write_time


create or replace view beli_after_adj as
select beli.pakan_id, sum(beli.total_isi) as total_isi
from beli_pakan beli
left join max_adj on max_adj.pakan_id = beli.pakan_id
left join pakan_inventory_adj adj on adj.id = max_adj.id
where beli.write_time >= adj.create_time
and beli.deleted = 0
group by beli.pakan_id


create or replace view tot_beli as
select pakan_id, sum(total_isi) as total
from beli_pakan
where deleted = 0
group by pakan_id


create or replace view mon as
select mon.pakan_id, sum(mon.jumlah_pakan) as total
from monitoring_pakan mon
left join max_adj on max_adj.pakan_id = mon.pakan_id
left join pakan_inventory_adj adj on adj.id = max_adj.id
where mon.write_time >= adj.create_time
and mon.deleted = 0
group by mon.pakan_id



create or replace view tot_mon as
select pakan_id, sum(jumlah_pakan) as total
from monitoring_pakan
where deleted = 0
group by pakan_id


------------------------------
--view untuk real stok obat--
------------------------------

create or replace view max_adj_obat as
SELECT max(id) as id, obat_id
from obat_inventory_adj adj
group by obat_id


create or replace view adj_obat as
select adj.obat_id, adj.write_time, adj.stok
from max_adj_obat
left join obat_inventory_adj adj on adj.id = max_adj_obat.id
group by adj.obat_id, adj.write_time


create or replace view beli_after_adj_obat as
select beli.obat_id, sum(beli.total_isi) as total_isi
from beli_obat beli
left join max_adj_obat max_adj on max_adj.obat_id = beli.obat_id
left join obat_inventory_adj adj on adj.id = max_adj.id
where beli.write_time >= adj.create_time
and beli.deleted = 0
group by beli.obat_id


create or replace view tot_beli_obat as
select obat_id, sum(total_isi) as total
from beli_obat
where deleted = 0
group by obat_id


--pending dulu
create or replace view mon_obat as
select bahan.obat_id, sum(bahan.jumlah) as total
from bahan_penolong bahan
left join max_adj_obat on max_adj_obat.obat_id = bahan.obat_id
left join obat_inventory_adj adj on adj.id = max_adj_obat.id
where bahan.write_time >= adj.create_time
and bahan.deleted = 0
group by bahan.obat_id


--pending dulu
create or replace view tot_mon_obat as
select obat_id, sum(jumlah) as total
from bahan_penolong
where deleted = 0
group by obat_id


create or replace view obat_real_stok as 
SELECT obat.id as obat_id, adj_obat.stok as stok_adj, beli_after_adj_obat.total_isi as total_pembelian_after_adj, tot_beli_obat.total as total_pembelian_obat
, mon_obat.total as monitoring_after_adj, tot_mon_obat.total as total_monitoring_obat,
ifnull(adj_obat.stok,0) + (case when beli_after_adj_obat.obat_id is null then ifnull(tot_beli_obat.total,0) else ifnull(beli_after_adj_obat.total_isi,0) end) - (case when mon_obat.obat_id is null then ifnull(tot_mon_obat.total,0) else ifnull(mon_obat.total,0) end) as final_stok
from obat
left join adj_obat on adj_obat.obat_id = obat.id
left join beli_after_adj_obat on beli_after_adj_obat.obat_id = obat.id
left join tot_beli_obat on tot_beli_obat.obat_id = obat.id
left join mon_obat on mon_obat.obat_id = obat.id
left join tot_mon_obat on tot_mon_obat.obat_id = obat.id
------------------------------
--view untuk inventory adjustment--
------------------------------

create or replace view inv_adj as
select 'p' as type, adj.pakan_id as ref_id, adj.stok, adj.create_time, pakan.name
from pakan_inventory_adj adj
left join pakan on pakan.id = adj.pakan_id
union
select 'o' as type, adj.obat_id as ref_id, adj.stok, adj.create_time, obat.name
from obat_inventory_adj adj
left join obat on obat.id = adj.obat_id