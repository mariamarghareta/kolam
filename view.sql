create or replace view v_pembelian as
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
;
------------------------------
--view untuk real stok pakan--
-----------------------------

create or replace view v_max_adj as
SELECT max(id) as id, pakan_id
from pakan_inventory_adj adj
group by pakan_id
;

create or replace view v_adj as
select adj.pakan_id, adj.write_time, adj.stok
from v_max_adj
left join pakan_inventory_adj adj on adj.id = v_max_adj.id
group by adj.pakan_id, adj.write_time
;

create or replace view v_beli_after_adj as
select beli.pakan_id, sum(beli.total_isi) as total_isi
from beli_pakan beli
left join v_max_adj on v_max_adj.pakan_id = beli.pakan_id
left join pakan_inventory_adj adj on adj.id = v_max_adj.id
where beli.write_time >= adj.create_time
and beli.deleted = 0
group by beli.pakan_id
;

create or replace view v_tot_beli as
select pakan_id, sum(total_isi) as total
from beli_pakan
where deleted = 0
group by pakan_id
;

create or replace view v_mon as
select mon.pakan_id, sum(mon.jumlah_pakan) as total
from monitoring_pakan mon
left join v_max_adj on v_max_adj.pakan_id = mon.pakan_id
left join pakan_inventory_adj adj on adj.id = v_max_adj.id
where mon.write_time >= adj.create_time
and mon.deleted = 0
group by mon.pakan_id
;


create or replace view v_tot_mon as
select pakan_id, sum(jumlah_pakan) as total
from monitoring_pakan
where deleted = 0
group by pakan_id
;

create or replace view v_pakan_real_stok as
SELECT pakan.id as pakan_id, adj.stok as stok_adj, beli_after_adj.total_isi as total_pembelian_after_adj, tot_beli.total as total_pembelian_pakan
, mon.total as monitoring_after_adj, tot_mon.total as total_monitoring_pakan,
ifnull(adj.stok,0) + (case when beli_after_adj.pakan_id is null then ifnull(tot_beli.total,0) else ifnull(beli_after_adj.total_isi,0) end) - (case when mon.pakan_id is null then ifnull(tot_mon.total,0) else ifnull(mon.total,0) end) as final_stok
from pakan
left join v_adj adj on adj.pakan_id = pakan.id
left join v_beli_after_adj beli_after_adj on beli_after_adj.pakan_id = pakan.id
left join v_tot_beli tot_beli on tot_beli.pakan_id = pakan.id
left join v_mon mon on mon.pakan_id = pakan.id
left join v_tot_mon tot_mon on tot_mon.pakan_id = pakan.id
;
------------------------------
--view untuk real stok obat--
------------------------------

create or replace view v_max_adj_obat as
SELECT max(id) as id, obat_id
from obat_inventory_adj adj
group by obat_id
;

create or replace view v_adj_obat as
select adj.obat_id, adj.write_time, adj.stok
from v_max_adj_obat
left join obat_inventory_adj adj on adj.id = v_max_adj_obat.id
group by adj.obat_id, adj.write_time
;

create or replace view v_beli_after_adj_obat as
select beli.obat_id, sum(beli.total_isi) as total_isi
from beli_obat beli
left join v_max_adj_obat max_adj on max_adj.obat_id = beli.obat_id
left join obat_inventory_adj adj on adj.id = max_adj.id
where beli.write_time >= adj.create_time
and beli.deleted = 0
group by beli.obat_id
;

create or replace view v_tot_beli_obat as
select obat_id, sum(total_isi) as total
from beli_obat
where deleted = 0
group by obat_id
;

create or replace view v_mon_obat as
select bahan.obat_id, sum(bahan.jumlah) as total
from bahan_penolong bahan
left join v_max_adj_obat on v_max_adj_obat.obat_id = bahan.obat_id
left join obat_inventory_adj adj on adj.id = v_max_adj_obat.id
where bahan.write_time >= adj.create_time
and bahan.deleted = 0
group by bahan.obat_id
;

create or replace view v_mon_sayur as
select bahan.obat_id, sum(bahan.jumlah) as total
from treatment_sayur bahan
left join v_max_adj_obat on v_max_adj_obat.obat_id = bahan.obat_id
left join obat_inventory_adj adj on adj.id = v_max_adj_obat.id
where bahan.write_time >= adj.create_time
and bahan.deleted = 0
group by bahan.obat_id
;

create or replace view v_tot_mon_obat as
select obat_id, sum(jumlah) as total
from bahan_penolong
where deleted = 0
group by obat_id
UNION
select obat_id, sum(jumlah) as total
from treatment_sayur
where deleted = 0
group by obat_id
;

create or replace view v_obat_real_stok as
SELECT obat.id as obat_id, adj_obat.stok as stok_adj, beli_after_adj_obat.total_isi as total_pembelian_after_adj, tot_beli_obat.total as total_pembelian_obat
, mon_obat.total + mon_sayur.total as monitoring_after_adj, tot_mon_obat.total as total_monitoring_obat,
ifnull(adj_obat.stok,0) + (case when beli_after_adj_obat.obat_id is null then ifnull(tot_beli_obat.total,0) else ifnull(beli_after_adj_obat.total_isi,0) end) - (case when mon_obat.obat_id is null then ifnull(tot_mon_obat.total,0) else ifnull(mon_obat.total,0) end) as final_stok
from obat
left join v_adj_obat adj_obat on adj_obat.obat_id = obat.id
left join v_beli_after_adj_obat beli_after_adj_obat on beli_after_adj_obat.obat_id = obat.id
left join v_tot_beli_obat tot_beli_obat on tot_beli_obat.obat_id = obat.id
left join v_mon_obat mon_obat on mon_obat.obat_id = obat.id
left join v_mon_sayur mon_sayur on mon_sayur.obat_id = obat.id
left join v_tot_mon_obat tot_mon_obat on tot_mon_obat.obat_id = obat.id
;
------------------------------
--view untuk inventory adjustment--
------------------------------

create or replace view v_inv_adj as
select 'p' as type, adj.pakan_id as ref_id, adj.stok, adj.create_time, pakan.name
from pakan_inventory_adj adj
left join pakan on pakan.id = adj.pakan_id
union
select 'o' as type, adj.obat_id as ref_id, adj.stok, adj.create_time, obat.name
from obat_inventory_adj adj
left join obat on obat.id = adj.obat_id
;

------------------------------
--view untuk monitoring--
------------------------------

create or replace view v_air_pagi as
select *
from monitoring_air
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu = 'PAGI' and deleted = 0
;

create or replace view v_air_sore as
select *
from monitoring_air
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu = 'SORE' and deleted = 0
;

create or replace view v_pakan_pagi AS
select *
from monitoring_pakan
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu_pakan = 'PAGI' and deleted = 0
;

create or replace view v_pakan_sore AS
select *
from monitoring_pakan
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu_pakan = 'SORE' and deleted = 0
;

create or replace view v_pakan_malam AS
select *
from monitoring_pakan
where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE , '%Y-%m-%d') and waktu_pakan = 'MALAM' and deleted = 0
;

create or replace view v_monitoring_all as
SELECT kolam.id as kolam_id, tebar.kode, case when air_pagi.id is not null then 1 else 0 end as air_pagi,
case when air_sore.id is not null then 1 else 0 end as air_sore,
case when pakan_pagi.id is not null then 1 else 0 end as pakan_pagi,
case when pakan_sore.id is not null then 1 else 0 end as pakan_sore,
case when pakan_malam.id is not null then 1 else 0 end as pakan_malam
from kolam
left join v_air_pagi air_pagi on air_pagi.kolam_id = kolam.id and air_pagi.tebar_id = kolam.tebar_id
left join v_air_sore air_sore on air_sore.kolam_id = kolam.id and air_sore.tebar_id = kolam.tebar_id
left join v_pakan_pagi pakan_pagi on pakan_pagi.kolam_id = kolam.id and pakan_pagi.tebar_id = kolam.tebar_id
left join v_pakan_sore pakan_sore on pakan_sore.kolam_id = kolam.id and pakan_sore.tebar_id = kolam.tebar_id
left join v_pakan_malam pakan_malam on pakan_malam.kolam_id = kolam.id and pakan_malam.tebar_id = kolam.tebar_id
left join tebar on tebar.id = kolam.tebar_id
where kolam.tebar_id != 0
;

-- untuk lihat laporan keuangan
create or replace view v_lap_keuangan as
select dt, keterangan, jumlah, harga, total, 1 as jenis
from jual
where jual.deleted = 0
UNION
select dt, name, jumlah_item, harga_per_item, total_harga, 0 as jenis
from beli_lain
where beli_lain.deleted = 0
UNION
select dt, obat.name, jumlah_item, harga_per_item, total_harga, 0 as jenis
from beli_obat
left join obat on obat.id = beli_obat.obat_id
where beli_obat.deleted = 0
UNION
select dt, pakan.name, jumlah_item, harga_per_item, total_harga, 0 as jenis
from beli_pakan
left join pakan on pakan.id = beli_pakan.pakan_id
where beli_pakan.deleted = 0
order by dt asc